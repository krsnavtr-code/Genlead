<?php

use App\Models\Employee;
use Illuminate\Support\Facades\Route;

Route::get('/test/agent-levels', function () {
    $agents = Employee::where('emp_job_role', 7)
        ->withCount(['directReferrals'])
        ->get(['id', 'emp_name', 'emp_job_role', 'referrer_id', 'agent_level', 'commission_rate', 'team_size']);

    // Calculate team size for each agent
    $agents->each(function ($agent) {
        $agent->calculated_team_size = $agent->getTeamSize();
    });

    return response()->json([
        'agents' => $agents,
        'total_agents' => $agents->count(),
    ]);
});
