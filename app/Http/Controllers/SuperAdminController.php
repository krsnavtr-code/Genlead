<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
{
    public function showLoginForm()
    {
        return view('superadmin.superadminlogin');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->is_superadmin) {
                return redirect()->route('superadmin.dashboard');
            }

            Auth::logout();
            return back()->withErrors([
                'email' => 'You do not have super admin access.',
            ]);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function dashboard()
    {
        // Auth middleware already handles superadmin check, so no need to double-check here
        $organizations = Organization::all();
        return view('superadmin.dashboard', compact('organizations'));
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('superadmin.login.form');
    }
}
