<?php

namespace App\Http\Controllers;

use App\Models\Organization;

class OrganizationDashboardController extends Controller
{
    public function index(Organization $organization)
    {
        return view('organization.dashboard', compact('organization'));
    }
}
