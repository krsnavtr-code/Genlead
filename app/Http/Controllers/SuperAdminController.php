<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;

class SuperAdminController extends Controller
{
    public function showsuperLoginForm()
    {
        return view('superadmin.superadminlogin');
    }

    public function superadminlogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Manually retrieve the superadmin user (role_id = 1)
        $user = \App\Models\User::where('email', $credentials['email'])
            ->where('role_id', 1)
            ->first();

        // Verify credentials using Bcrypt
        if ($user && \Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            // Log the user in
            Auth::login($user);
            $request->session()->regenerate();
            
            return redirect()->route('superadmin.dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records or you do not have super admin access.',
        ]);
    }

    public function dashboard()
    {
        if (!Auth::check() || !Auth::user()->is_superadmin) {
            return redirect()->route('superadmin.login.form');
        }

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
