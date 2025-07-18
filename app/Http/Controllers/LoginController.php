<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Organization;

class LoginController extends Controller
{
 
    public function showLoginForm()
    {
        return view('organization.login');
    }
public function orglogin(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::guard('superadmin')->attempt($credentials)) {
        $organization = Organization::first();

        return redirect()->route('organization.dashboard', ['organization' => $organization->slug]);
    }

    return redirect()->back()->withErrors(['Invalid credentials']);
}

}
