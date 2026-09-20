<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    /**
     * Show the account settings / change password form.
     */
    public function edit()
    {
        $account = $this->currentAccount();
        $isStudent = Auth::guard('student')->check();

        return view('account.edit', [
            'account' => $account,
            'isStudent' => $isStudent,
        ]);
    }

    /**
     * Handle the password change.
     */
    public function update(Request $request)
    {
        $account = $this->currentAccount();

        $validated = $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if (! Hash::check($validated['current_password'], $account->password)) {
            return back()->withErrors(['current_password' => 'Your current password is incorrect.']);
        }

        $account->update([
            'password' => bcrypt($validated['new_password']),
            'must_change_password' => false,
        ]);

        return back()->with('success', 'Password updated successfully.');
    }

    /**
     * Get the currently logged-in account, whichever guard matched.
     */
    private function currentAccount()
    {
        if (Auth::guard('web')->check()) {
            return Auth::guard('web')->user();
        }

        return Auth::guard('student')->user();
    }
}