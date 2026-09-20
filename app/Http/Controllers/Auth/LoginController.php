<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class LoginController extends Controller
{
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

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);

            return back()->withErrors([
                'email' => "Too many login attempts. Please try again in {$minutes} minute" . ($minutes > 1 ? 's' : '') . '.',
            ])->onlyInput('email');
        }

        // Try staff/department guard first
        if (Auth::guard('web')->attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            Auth::guard('student')->logout();
            $request->session()->regenerate();

            $user = Auth::guard('web')->user();

            if ($user->must_change_password) {
                return redirect()->route('password.force-change');
            }

            return redirect()->intended(route('staff.dashboard'));
        }

        // Try student guard second
        if (Auth::guard('student')->attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            Auth::guard('web')->logout();
            $request->session()->regenerate();

            $student = Auth::guard('student')->user();

            if ($student->must_change_password) {
                return redirect()->route('password.force-change');
            }

            return redirect()->intended(route('student.dashboard'));
        }

        RateLimiter::hit($throttleKey, 180);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log the user out of whichever guard is active.
     */
    public function logout(Request $request)
    {
        if (Auth::guard('web')->check()) {
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