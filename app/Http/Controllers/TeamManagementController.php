<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TeamManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the team management dashboard
     */
    public function index()
    {
        // Only allow team leaders (role 6) to access
        if (session('emp_job_role') !== 6) {
            abort(403, 'Unauthorized access.');
        }

        // Get the current team leader's ID
        $teamLeaderId = Auth::id();

        // Get all agents (role 2) that report to this team leader
        $teamMembers = Employee::where('emp_job_role', 2) // Agents
            ->where('reports_to', $teamLeaderId)
            ->orderBy('emp_name')
            ->get();

        return view('team_management.index', [
            'teamMembers' => $teamMembers,
            'title' => 'Team Management'
        ]);
    }

    /**
     * Show the form for editing a team member
     */
    public function edit($id)
    {
        if (session('emp_job_role') !== 6) {
            abort(403, 'Unauthorized access.');
        }

        $teamMember = Employee::findOrFail($id);

        // Verify that this team member reports to the current team leader
        if ($teamMember->reports_to !== Auth::id()) {
            abort(403, 'Unauthorized access to this team member.');
        }

        return view('team_management.edit', [
            'teamMember' => $teamMember,
            'title' => 'Edit Team Member'
        ]);
    }

    /**
     * Update the specified team member
     */
    public function update(Request $request, $id)
    {
        if (session('emp_job_role') !== 6) {
            abort(403, 'Unauthorized access.');
        }

        $teamMember = Employee::findOrFail($id);

        // Verify that this team member reports to the current team leader
        if ($teamMember->reports_to !== Auth::id()) {
            abort(403, 'Unauthorized access to this team member.');
        }

        $validated = $request->validate([
            'emp_name' => 'required|string|max:255',
            'emp_email' => 'required|email|unique:employees,emp_email,' . $id,
            'emp_phone' => 'required|string|max:20',
            'emp_status' => 'required|in:active,inactive',
        ]);

        $teamMember->update($validated);

        return redirect()->route('team.management')
            ->with('success', 'Team member updated successfully');
    }

    /**
     * Display team performance metrics
     */
    public function performance()
    {
        if (session('emp_job_role') !== 6) {
            abort(403, 'Unauthorized access.');
        }

        $teamLeaderId = Auth::id();
        $teamMembers = Employee::where('emp_job_role', 2)
            ->where('reports_to', $teamLeaderId)
            ->withCount(['leads as total_leads'])
            ->withCount(['leads as converted_leads' => function($query) {
                $query->where('status', 'converted');
            }])
            ->get();

        return view('team_management.performance', [
            'teamMembers' => $teamMembers,
            'title' => 'Team Performance'
        ]);
    }
    
    /**
     * Show the form to assign agents to team leaders (Admin only)
     */
    public function showAssignAgentsForm()
    {
        if (session('emp_job_role') !== 1) {
            abort(403, 'Unauthorized access.');
        }

        // Get all team leaders (role 6)
        $teamLeaders = Employee::where('emp_job_role', 6)
            ->orderBy('emp_name')
            ->get();

        // Get all agents (role 2)
        $agents = Employee::where('emp_job_role', 2)
            ->orderBy('emp_name')
            ->get();

        return view('team_management.assign_agents', [
            'teamLeaders' => $teamLeaders,
            'agents' => $agents,
            'title' => 'Assign Agents to Team Leaders'
        ]);
    }

    /**
     * Process the assignment of agents to team leaders (Admin only)
     */
    public function assignAgentsToTeamLeader(Request $request)
    {
        if (session('emp_job_role') !== 1) {
            abort(403, 'Unauthorized access.');
        }

        $validated = $request->validate([
            'team_leader_id' => 'required|exists:employees,id,emp_job_role,6', // Must be a team leader
            'agent_ids' => 'required|array',
            'agent_ids.*' => 'exists:employees,id,emp_job_role,2', // Must be an agent
        ]);

        // Update the reports_to field for selected agents
        Employee::whereIn('id', $validated['agent_ids'])
            ->update(['reports_to' => $validated['team_leader_id']]);

        return redirect()->route('admin.assign.agents.form')
            ->with('success', 'Agents have been successfully assigned to the team leader.');
    }
}
