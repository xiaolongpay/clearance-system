<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    /**
     * Handle an incoming request. Forces the user to change their password
     * before accessing anything else, on every request, not just at login.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $account = Auth::guard('web')->user() ?? Auth::guard('student')->user();

        if ($account && $account->must_change_password) {
            if (! $request->routeIs('account.edit') && ! $request->routeIs('account.update') && ! $request->routeIs('logout') && ! $request->routeIs('login')) {
                return redirect()->route('account.edit');
            }
        }

        return $next($request);
    }
}