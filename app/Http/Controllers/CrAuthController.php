<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CrAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::guard('cr_user')->check()) {
            return redirect()->route('kyc.index');
        }
        return view('accounts-admin.cr-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('cr_user')->attempt($credentials)) {
            return redirect()->route('kyc.index');
        }

        return back()->withInput($request->only('email'))->withErrors([
            'email' => 'Invalid email address or password.',
        ]);
    }

    public function dashboard()
    {
        return redirect()->route('kyc.index');
    }

    public function logout(Request $request)
    {
        Auth::guard('cr_user')->logout();
        return redirect()->route('cr.login');
    }
}
