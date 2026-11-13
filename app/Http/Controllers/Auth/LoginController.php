<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /**
     * Wrong tries allowed before the login is paused, and how long the pause lasts.
     */
    private const MAX_ATTEMPTS = 3;
    private const PAUSE_SECONDS = 300;

    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return response()
            ->view('auth.login')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache');
    }

    /**
     * Handle a login attempt against both guards.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $throttleKey = Str::lower($credentials['email']) . '|' . $request->ip();
        $pauseKey = 'login-paused|' . $throttleKey;

        // The login is paused after too many wrong tries
        $pausedUntil = Cache::get($pauseKey);

        if ($pausedUntil && $pausedUntil > time()) {
            $minutes = (int) ceil(($pausedUntil - time()) / 60);

            return back()->withErrors([
                'email' => "Too many wrong attempts. Please try again in {$minutes} minute" . ($minutes > 1 ? 's' : '') . '.',
            ])->onlyInput('email');
        }

        // Try staff/department guard first
        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            Cache::forget($pauseKey);
            Auth::guard('student')->logout();
            $request->session()->regenerate();

            $user = Auth::guard('web')->user();

            AuditLog::record('auth.login', null, 'Staff sign-in');

            if ($user->must_change_password) {
                return redirect()->route('password.force-change');
            }

            return redirect()->intended(route('staff.dashboard'));
        }

        // Try student guard second
        if (Auth::guard('student')->attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            Cache::forget($pauseKey);
            Auth::guard('web')->logout();
            $request->session()->regenerate();

            $student = Auth::guard('student')->user();

            if ($student->must_change_password) {
                return redirect()->route('password.force-change');
            }

            return redirect()->intended(route('student.dashboard'));
        }

        // Only failed sign-ins on a staff account are worth recording
        if (User::where('email', $credentials['email'])->exists()) {
            AuditLog::record('auth.login_failed', null, 'Wrong password', $credentials['email']);
        }

        // Count this wrong try, and tell the person how many tries are left
        RateLimiter::hit($throttleKey, 900);
        $left = max(0, self::MAX_ATTEMPTS - RateLimiter::attempts($throttleKey));

        if ($left === 0) {
            RateLimiter::clear($throttleKey);
            Cache::put($pauseKey, time() + self::PAUSE_SECONDS, self::PAUSE_SECONDS);

            $minutes = self::PAUSE_SECONDS / 60;

            return back()->withErrors([
                'email' => "Too many wrong attempts. Login is paused for {$minutes} minutes. Please try again later.",
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records. You have ' . $left . ' attempt' . ($left > 1 ? 's' : '') . ' left before login is paused for ' . (self::PAUSE_SECONDS / 60) . ' minutes.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out of whichever guard is active.
     */
    public function logout(Request $request)
    {
        if (Auth::guard('web')->check()) {
            AuditLog::record('auth.logout', null, 'Staff sign-out');
            Auth::guard('web')->logout();
        }

        if (Auth::guard('student')->check()) {
            Auth::guard('student')->logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}