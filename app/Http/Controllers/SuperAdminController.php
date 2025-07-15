<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;

class SuperAdminController extends Controller
{
    public function dashboard()
{
    $organizations = Organization::all(); // Fetch all organizations
    return view('superadmin.dashboard', compact('organizations'));
}
    
    public function login(Request $request)
    {
        // Add your login logic here
        return response()->json(['message' => 'Login successful!']);
    }

    
}

