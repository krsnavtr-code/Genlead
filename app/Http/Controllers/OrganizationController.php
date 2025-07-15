<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Organization;
use Illuminate\Support\Str; // Add this at the top


class OrganizationController extends Controller
{
    // Show registration form

    public function showRegisterForm()
{
    return view('organization.orgregister'); // Make sure this Blade file exists
}

public function register(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:organizations,email',
        'contact_number' => 'required|string|max:15',
    ]);

    // Add role number manually
    $validated['role_no'] = 2;

    // Generate slug from name
    $validated['slug'] = Str::slug($validated['name'], '-');

    Organization::create($validated);

    return back()->with('success', 'Organization registered successfully!');
}

    
}
