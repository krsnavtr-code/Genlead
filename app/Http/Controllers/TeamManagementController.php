<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\personal\Lead;
use App\Models\personal\FollowUp;
use App\Models\personal\LeadStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class TeamManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the team management dashboard
     */
    /**
     * Display the team management dashboard
     */
    public function index()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Debug logging
        Log::info('Team Management Access Attempt', [
            'user_id' => Auth::id(),
            'role' => session('emp_job_role'),
            'all_session' => session()->all()
        ]);

        // Allow both admin (role 1) and team leaders (role 6) to access
        if (!in_array(session('emp_job_role'), [1, 6])) {
            abort(403, 'You do not have permission to access this page. Only Team Leaders and Admins can access this section.');
        }

        // Get all active lead statuses for the dropdown
        $leadStatuses = LeadStatus::active()->ordered()->get();
        
        // Check if user is admin (role 1) or team leader (role 6)
        $isAdmin = session('emp_job_role') == 1;
        $teamLeaderId = Auth::id();

        if (!$isAdmin) {
            // For team leaders, only show their direct reports
            $query = Employee::where('emp_job_role', 2) // Agents
                ->where('reports_to', $teamLeaderId);
                
            $teamMembers = $query->withCount(['leads as total_leads'])
                ->withCount(['leads as converted_leads' => function($query) {
                    $convertedStatus = LeadStatus::where('name', 'Converted')->first();
                    $query->when($convertedStatus, function($q) use ($convertedStatus) {
                        $q->where('status_id', $convertedStatus->id);
                    }, function($q) {
                        $q->where('status', 'converted');
                    });
                }])
                ->withCount(['leads as pending_leads' => function($query) {
                    $pendingStatus = LeadStatus::where('name', 'Pending')->first();
                    $query->when($pendingStatus, function($q) use ($pendingStatus) {
                        $q->where('status_id', $pendingStatus->id);
                    }, function($q) {
                        $q->where('status', 'pending');
                    });
                }])
                ->withCount(['leads as rejected_leads' => function($query) {
                    $rejectedStatus = LeadStatus::where('name', 'Rejected')->first();
                    $query->when($rejectedStatus, function($q) use ($rejectedStatus) {
                        $q->where('status_id', $rejectedStatus->id);
                    }, function($q) {
                        $q->where('status', 'rejected');
                    });
                }])
                ->get();
                
            return view('team_management.index', compact('teamMembers', 'leadStatuses'));
        } else {
            // For admins, separate agents into with team and without team
            $agentsWithTeam = Employee::where('emp_job_role', 2)
                ->whereNotNull('reports_to')
                ->with(['reportsTo' => function($q) {
                    $q->select('id', 'emp_name');
                }])
                ->withCount(['leads as total_leads'])
                ->withCount(['leads as converted_leads' => function($query) {
                    $convertedStatus = LeadStatus::where('name', 'Converted')->first();
                    $query->when($convertedStatus, function($q) use ($convertedStatus) {
                        $q->where('status_id', $convertedStatus->id);
                    }, function($q) {
                        $q->where('status', 'converted');
                    });
                }])
                ->withCount(['leads as pending_leads' => function($query) {
                    $pendingStatus = LeadStatus::where('name', 'Pending')->first();
                    $query->when($pendingStatus, function($q) use ($pendingStatus) {
                        $q->where('status_id', $pendingStatus->id);
                    }, function($q) {
                        $q->where('status', 'pending');
                    });
                }])
                ->withCount(['leads as rejected_leads' => function($query) {
                    $rejectedStatus = LeadStatus::where('name', 'Rejected')->first();
                    $query->when($rejectedStatus, function($q) use ($rejectedStatus) {
                        $q->where('status_id', $rejectedStatus->id);
                    }, function($q) {
                        $q->where('status', 'rejected');
                    });
                }])
                ->get();

            $agentsWithoutTeam = Employee::where('emp_job_role', 2)
                ->whereNull('reports_to')
                ->withCount(['leads as total_leads'])
                ->withCount(['leads as converted_leads' => function($query) {
                    $convertedStatus = LeadStatus::where('name', 'Converted')->first();
                    $query->when($convertedStatus, function($q) use ($convertedStatus) {
                        $q->where('status_id', $convertedStatus->id);
                    }, function($q) {
                        $q->where('status', 'converted');
                    });
                }])
                ->withCount(['leads as pending_leads' => function($query) {
                    $pendingStatus = LeadStatus::where('name', 'Pending')->first();
                    $query->when($pendingStatus, function($q) use ($pendingStatus) {
                        $q->where('status_id', $pendingStatus->id);
                    }, function($q) {
                        $q->where('status', 'pending');
                    });
                }])
                ->withCount(['leads as rejected_leads' => function($query) {
                    $rejectedStatus = LeadStatus::where('name', 'Rejected')->first();
                    $query->when($rejectedStatus, function($q) use ($rejectedStatus) {
                        $q->where('status_id', $rejectedStatus->id);
                    }, function($q) {
                        $q->where('status', 'rejected');
                    });
                }])
                ->get();
                
            return view('team_management.index', compact('agentsWithTeam', 'agentsWithoutTeam', 'leadStatuses'));
        }
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

    /**
     * Display the team dashboard with lead statistics
     */
    public function dashboard()
    {
        // Only allow team leaders (role 6) to access
        if (session('emp_job_role') !== 6) {
            abort(403, 'Unauthorized access.');
        }

        // Get the current team leader's ID
        $teamLeaderId = Auth::id();

        // Get all agents that report to this team leader
        $agentIds = Employee::where('reports_to', $teamLeaderId)
            ->where('emp_job_role', 2) // Only agents
            ->pluck('id');

        // Get all active statuses with counts
        $statuses = LeadStatus::active()
            ->withCount(['leads' => function($query) use ($agentIds) {
                $query->whereIn('agent_id', $agentIds);
            }])
            ->orderBy('sort_order')
            ->get();

        // Calculate total leads from status counts
        $totalLeads = $statuses->sum('leads_count');
        
        // Get today's and this week's leads
        $todayLeads = Lead::whereIn('agent_id', $agentIds)
            ->whereDate('created_at', today())
            ->count();
            
        $thisWeekLeads = Lead::whereIn('agent_id', $agentIds)
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();

        // Get recent leads with agent and status info
        $recentLeads = Lead::whereIn('agent_id', $agentIds)
            ->with([
                'agent' => function($query) {
                    $query->select('id', 'emp_fname', 'emp_lname');
                },
                'status' => function($query) {
                    $query->select('id', 'name', 'color');
                }
            ])
            ->latest()
            ->take(10)
            ->get();

        // Prepare data for the status chart
        $chartData = [
            'labels' => $statuses->pluck('name'),
            'data' => $statuses->pluck('leads_count'),
            'colors' => $statuses->pluck('color'),
            'total' => $totalLeads
        ];

        return view('team_management.dashboard', [
            'totalLeads' => $totalLeads,
            'todayLeads' => $todayLeads,
            'thisWeekLeads' => $thisWeekLeads,
            'statuses' => $statuses,
            'recentLeads' => $recentLeads,
            'chartData' => $chartData,
            'title' => 'Team Dashboard'
        ]);
    }

    /**
     * Show leads for a specific team member with advanced filtering
     */
    public function memberLeads($id, Request $request)
    {
        if (session('emp_job_role') !== 6) {
            abort(403, 'Unauthorized access.');
        }

        $teamMember = Employee::findOrFail($id);

        // Verify that this team member reports to the current team leader
        if ($teamMember->reports_to !== Auth::id()) {
            abort(403, 'Unauthorized access to this team member\'s leads.');
        }

        // Get filter parameters from request
        $statusId = $request->input('status_id');
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        // Get all active statuses for the filter dropdown
        $allStatuses = LeadStatus::active()->ordered()->get();

        // Build the base query
        $query = Lead::where('agent_id', $id)
            ->with([
                'status' => function($q) {
                    $q->select('id', 'name', 'color');
                },
                'followUps' => function($q) {
                    $q->orderBy('follow_up_time', 'desc')
                      ->select('id', 'lead_id', 'follow_up_time', 'status', 'comments');
                },
                'statusHistory' => function($q) {
                    $q->with('performer')
                      ->latest()
                      ->take(5);
                }
            ]);


        // Apply status filter
        if ($statusId) {
            $query->where('status_id', $statusId);
        }

        // Apply date range filter
        if ($startDate) {
            $query->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('created_at', '<=', $endDate);
        }

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhereHas('status', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Apply sorting
        $validSortFields = ['first_name', 'last_name', 'email', 'created_at', 'updated_at'];
        $sortBy = in_array($sortBy, $validSortFields) ? $sortBy : 'created_at';
        $sortOrder = strtolower($sortOrder) === 'asc' ? 'asc' : 'desc';
        
        $query->orderBy($sortBy, $sortOrder);

        // Get paginated results
        $leads = $query->paginate(15)->withQueryString();

        // Get status counts for the member with status details
        $statusCounts = Lead::where('agent_id', $id)
            ->select('status_id', DB::raw('count(*) as count'))
            ->with(['status' => function($q) {
                $q->select('id', 'name', 'color');
            }])
            ->groupBy('status_id')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->status_id => [
                        'count' => $item->count,
                        'name' => $item->status->name ?? 'Unknown',
                        'color' => $item->status->color ?? '#6c757d'
                    ]
                ];
            });

        // Get all statuses for the filter dropdown
        $allStatuses = LeadStatus::active()->ordered()->get();

        return view('team_management.member_leads', [
            'teamMember' => $teamMember,
            'leads' => $leads,
            'statuses' => $allStatuses,
            'statusCounts' => $statusCounts,
            'filters' => [
                'status_id' => $statusId,
                'search' => $search,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder
            ],
            'title' => $teamMember->emp_fname . ' ' . $teamMember->emp_lname . "'s Leads"
        ]);
    }

    /**
     * Display all followups for a specific team member
     */
    public function memberFollowups($id)
    {
        if (session('emp_job_role') !== 6) {
            abort(403, 'Unauthorized access.');
        }

        $teamMember = Employee::findOrFail($id);

        // Verify that this team member reports to the current team leader
        if ($teamMember->reports_to !== Auth::id()) {
            abort(403, 'Unauthorized access to this team member\'s followups.');
        }

        // Get today's followups
        $todayFollowups = FollowUp::with(['lead', 'agent'])
            ->where('agent_id', $id)
            ->whereDate('follow_up_time', now()->toDateString())
            ->orderBy('follow_up_time', 'asc')
            ->get();

        // Get upcoming followups (after today)
        $upcomingFollowups = FollowUp::with(['lead', 'agent'])
            ->where('agent_id', $id)
            ->where('follow_up_time', '>', now()->endOfDay())
            ->orderBy('follow_up_time', 'asc')
            ->get();

        // Get past followups (before today)
        $pastFollowups = FollowUp::with(['lead', 'agent'])
            ->where('agent_id', $id)
            ->where('follow_up_time', '<', now()->startOfDay())
            ->orderBy('follow_up_time', 'desc')
            ->paginate(15);

        return view('team_management.member_followups', [
            'teamMember' => $teamMember,
            'todayFollowups' => $todayFollowups,
            'upcomingFollowups' => $upcomingFollowups,
            'pastFollowups' => $pastFollowups,
            'title' => 'Followups - ' . $teamMember->emp_name
        ]);
    }

    /**
     * Show lead statistics for the team
     */
    public function leadStats()
    {
        if (session('emp_job_role') !== 6) {
            abort(403, 'Unauthorized access.');
        }

        $teamLeaderId = Auth::id();
        $startDate = request('start_date', now()->subDays(30)->format('Y-m-d'));
        $endDate = request('end_date', now()->format('Y-m-d'));

        $teamMembers = Employee::where('reports_to', $teamLeaderId)
            ->withCount(['leads as total_leads' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->withCount(['leads as converted_leads' => function($query) use ($startDate, $endDate) {
                $query->where('status', 'converted')
                    ->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->withCount(['leads as pending_leads' => function($query) use ($startDate, $endDate) {
                $query->where('status', 'pending')
                    ->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->get();

        // Calculate conversion rates
        $teamMembers = $teamMembers->map(function($member) {
            $member->conversion_rate = $member->total_leads > 0
                ? round(($member->converted_leads / $member->total_leads) * 100, 2)
                : 0;
            return $member;
        });

        // Get lead status distribution
        $statusDistribution = Lead::whereIn('agent_id', $teamMembers->pluck('id'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status');

        // Get daily lead count for the last 30 days
        $dailyLeads = Lead::whereIn('agent_id', $teamMembers->pluck('id'))
            ->whereBetween('created_at', [now()->subDays(30), now()])
            ->selectRaw('DATE(created_at) as date, count(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('team_management.lead_stats', [
            'teamMembers' => $teamMembers,
            'statusDistribution' => $statusDistribution,
            'dailyLeads' => $dailyLeads,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'title' => 'Team Lead Statistics'
        ]);
    }

    /**
     * Transfer leads between team members
     */
    /**
     * Display the agent referral chain
     */
    public function agentReferralChain()
    {
        // Only allow admins (1) and team leaders (2) to access
        if (!in_array(session('emp_job_role'), [1, 2])) {
            abort(403, 'Unauthorized access.');
        }

        $currentUser = Auth::user();
        
        // Get all agents in the system with their referrals preloaded
        $allAgents = Employee::where('emp_job_role', 2)
            ->with(['referrals' => function($query) {
                $query->where('emp_job_role', 2);
            }])
            ->get()
            ->keyBy('id');
        
        // Add direct referrals count to each agent
        foreach ($allAgents as $agent) {
            $agent->direct_referrals_count = $agent->referrals->count();
        }
        
        // Build the referral tree
        $referralTree = [];
        $processedAgents = [];
        
        if ($currentUser->emp_job_role == 1) { // If admin, show all agents
            $rootAgents = $allAgents->whereNull('referrer_id');
        } else {
            // Only show direct reports for non-admin users
            $rootAgents = $allAgents->where('referrer_id', $currentUser->id);
        }
        
        foreach ($rootAgents as $agent) {
            if (!in_array($agent->id, $processedAgents)) {
                $referralTree[] = $this->buildReferralTree($agent, $allAgents, $processedAgents);
                $processedAgents[] = $agent->id;
            }
        }

        return view('team.referral-chain', [
            'referralTree' => $referralTree,
            'currentUser' => $currentUser
        ]);
    }

    /**
     * Recursively build the referral tree
     */
    private function buildReferralTree($agent, $allAgents, &$processedAgents, $level = 0)
    {
        // Limit the depth to prevent infinite recursion
        if ($level > 10) {
            $agent->referrals = collect();
            return $agent;
        }
        
        // Mark this agent as processed
        if (!in_array($agent->id, $processedAgents)) {
            $processedAgents[] = $agent->id;
        }

        // Process referrals
        $processedReferrals = collect();
        foreach ($agent->referrals as $referral) {
            if (!in_array($referral->id, $processedAgents)) {
                $processedReferrals->push($this->buildReferralTree($referral, $allAgents, $processedAgents, $level + 1));
            }
        }
        
        $agent->setRelation('referrals', $processedReferrals);
        return $agent;
    }

    public function transferLeads(Request $request)
    {
        if (session('emp_job_role') !== 6) {
            return response()->json(['success' => false, 'message' => 'Unauthorized access.'], 403);
        }

        $request->validate([
            'from_agent_id' => 'required|exists:employees,id',
            'to_agent_id' => 'required|exists:employees,id',
            'lead_ids' => 'required|array',
            'lead_ids.*' => 'exists:leads,id',
            'transfer_notes' => 'nullable|string|max:500'
        ]);

        // Verify both agents are in the same team
        $teamLeaderId = Auth::id();
        $fromAgent = Employee::where('id', $request->from_agent_id)
            ->where('reports_to', $teamLeaderId)
            ->firstOrFail();

        $toAgent = Employee::where('id', $request->to_agent_id)
            ->where('reports_to', $teamLeaderId)
            ->firstOrFail();

        // Update leads
        $updated = Lead::whereIn('id', $request->lead_ids)
            ->where('agent_id', $fromAgent->id)
            ->update([
                'agent_id' => $toAgent->id,
                'transfer_notes' => $request->transfer_notes,
                'transferred_at' => now(),
                'transferred_by' => $teamLeaderId
            ]);

        if ($updated > 0) {
            // Log the transfer
            foreach ($request->lead_ids as $leadId) {
                // Add to lead history
                \App\Models\personal\LeadHistory::create([
                    'lead_id' => $leadId,
                    'action' => 'transferred',
                    'description' => "Lead transferred from {$fromAgent->emp_name} to {$toAgent->emp_name}",
                    'performed_by' => $teamLeaderId,
                    'details' => [
                        'from_agent_id' => $fromAgent->id,
                        'to_agent_id' => $toAgent->id,
                        'notes' => $request->transfer_notes
                    ]
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully transferred {$updated} leads to {$toAgent->emp_name}"
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No leads were transferred. Please check the lead IDs and try again.'
        ]);
    }
}
