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
     * Display lead details for a specific team member
     *
     * @param int $id The ID of the team member
     * @return \Illuminate\View\View
     */
    /**
     * Display lead details for a specific lead
     *
     * @param int $id The ID of the lead
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function viewLeadDetails($id)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $emp_job_role = session('emp_job_role');
        
        // Get the lead with relationships
        $lead = Lead::with([
            'status',
            'agent',
            'followUps' => function($query) {
                $query->orderBy('follow_up_time', 'desc');
            },
            'followUps.createdBy'
        ])->findOrFail($id);
        
        // Check access based on role
        if ($emp_job_role == 1) {
            // Admin has full access
        } elseif ($emp_job_role == 6) {
            // Team Leader - check if this lead belongs to their team
            $teamMemberIds = Employee::where('reports_to', $user->id)->pluck('id')->toArray();
            if (!in_array($lead->agent_id, $teamMemberIds) && $lead->agent_id != $user->id) {
                abort(403, 'You can only view leads of your team members.');
            }
        } elseif ($emp_job_role == 7) {
            // Chain team agent - verify they have access to this lead
            $agent = Employee::find($lead->agent_id);
            if ($agent->referrer_id != $user->id && $lead->agent_id != $user->id) {
                abort(403, 'Unauthorized access to this lead.');
            }
        } else {
            // Regular agent - can only view their own leads
            if ($lead->agent_id != $user->id) {
                abort(403, 'You can only view your own leads.');
            }
        }
        
        // Get all statuses for the status dropdown
        $statuses = LeadStatus::where('is_active', 1)->get();
        
        return view('team_management.lead_details', compact('lead', 'statuses'));
    }
    
    /**
     * Display lead details for a specific team member
     *
     * @param int $id The ID of the team member
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function memberLeadsDetails($id)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $emp_job_role = session('emp_job_role');
        
        // Get the agent
        $agent = Employee::findOrFail($id);
        
        // Check access based on role
        if ($emp_job_role == 1) {
            // Admin has full access
        } elseif ($emp_job_role == 6) {
            // Team Leader - check if this agent is in their team
            $teamMemberIds = Employee::where('reports_to', $user->id)->pluck('id')->toArray();
            if (!in_array($agent->id, $teamMemberIds) && $agent->id != $user->id) {
                abort(403, 'You can only view leads of your team members.');
            }
        } elseif ($emp_job_role == 7) {
            // Chain team agent - verify they have access to this agent's data
            if ($agent->referrer_id != $user->id && $agent->id != $user->id) {
                abort(403, 'Unauthorized access to this agent\'s data.');
            }
        } else {
            abort(403, 'Unauthorized access.');
        }
        
        // Get leads for this agent with their status counts
        try {
            // First, let's check if the agent exists
            if (!$agent) {
                throw new \Exception('Agent not found');
            }
            
            // Log the agent details for debugging
            Log::info('Fetching leads for agent', [
                'agent_id' => $agent->id,
                'agent_name' => $agent->emp_name,
                'agent_email' => $agent->emp_email
            ]);
            
            // Get leads where agent_id matches the agent's ID with eager loading
            $leadsQuery = Lead::where('agent_id', $agent->id)
                ->with([
                    'status' => function($query) {
                        $query->select('id', 'name', 'color');
                    }
                ])
                ->withCount(['followUps as total_followups'])
                ->orderBy('created_at', 'desc');
            
            // Execute the query with pagination
            $leads = $leadsQuery->paginate(20);
            
            // Transform the leads collection to ensure status is properly loaded
            $leads->getCollection()->transform(function ($lead) {
                // Make sure status is properly loaded
                if ($lead->relationLoaded('status') && !$lead->status) {
                    $lead->unsetRelation('status');
                }
                return $lead;
            });
            
            // Log the results summary (not the full leads array to avoid log bloat)
            Log::info('Leads query executed', [
                'agent_id' => $agent->id,
                'leads_count' => $leads->total(),
                'current_page' => $leads->currentPage(),
                'per_page' => $leads->perPage(),
                'has_more_pages' => $leads->hasMorePages()
            ]);
                
        } catch (\Exception $e) {
            Log::error('Error fetching leads: ' . $e->getMessage());
            // Return the view with an empty collection and paginate manually
            $leads = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]), // items
                0, // total
                20, // per page
                \Illuminate\Pagination\Paginator::resolveCurrentPage() // current page
            );
            session()->flash('error', 'Error fetching leads: ' . $e->getMessage());
        }
        
        // Get lead statuses for filter with only necessary fields
        $statuses = LeadStatus::select('id', 'name', 'color', 'is_active')
            ->where('is_active', true)
            ->get()
            ->keyBy('id')
            ->toArray();
            
        // Determine which view to use based on role
        $isTeamLeader = ($emp_job_role == 6);
        $view = $isTeamLeader ? 'team_management.tl_member_leads' : 'team_management.member_leads_details';
        
        // Log view rendering details
        Log::info('Rendering leads view', [
            'view' => $view,
            'agent_id' => $agent->id,
            'leads_count' => $leads->count(),
            'statuses_count' => count($statuses),
            'is_team_leader_view' => $isTeamLeader
        ]);
        
        return view($view, [
            'agent' => $agent,
            'leads' => $leads,
            'statuses' => $statuses,
            'isTeamLeaderView' => $isTeamLeader
        ]);
    }
    
    /**
     * Display agent referral leads details
     */
    public function agentReferralLeadsDetails()
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        $emp_job_role = session('emp_job_role');
        
        // Check if user is admin (role 1) or chain team agent (role 7)
        if ($emp_job_role != 1 && $emp_job_role != 7) {
            abort(403, 'Unauthorized access.');
        }
        
        // For admin, get all chain team agents (role 7)
        // For chain team agent, get their referrals
        if ($emp_job_role == 1) {
            $agents = Employee::where('emp_job_role', 7)
                ->withCount(['leads as total_leads'])
                ->withCount(['leads as converted_leads_count' => function($query) {
                    $query->where('status', 'converted');
                }])
                ->withCount(['leads as pending_leads_count' => function($query) {
                    $query->where('status', 'pending');
                }])
                ->withCount(['leads as rejected_leads_count' => function($query) {
                    $query->where('status', 'rejected');
                }])
                ->get();
        } else {
            // Get the agent's direct referrals that are chain team agents (role 7)
            $agents = Employee::where('referrer_id', $user->id)
                ->where('emp_job_role', 7) // Only get chain team agent referrals
                ->withCount(['leads as total_leads'])
                ->withCount(['leads as converted_leads_count' => function($query) {
                    $query->where('status', 'converted');
                }])
                ->withCount(['leads as pending_leads_count' => function($query) {
                    $query->where('status', 'pending');
                }])
                ->withCount(['leads as rejected_leads_count' => function($query) {
                    $query->where('status', 'rejected');
                }])
                ->get();
            
            // Include the current agent in the results if they are a chain team agent
            if ($user->emp_job_role == 7) {
                $currentAgent = Employee::where('id', $user->id)
                    ->withCount(['leads as total_leads'])
                    ->withCount(['leads as converted_leads_count' => function($query) {
                        $query->where('status', 'converted');
                    }])
                    ->withCount(['leads as pending_leads_count' => function($query) {
                        $query->where('status', 'pending');
                    }])
                    ->withCount(['leads as rejected_leads_count' => function($query) {
                        $query->where('status', 'rejected');
                    }])
                    ->first();
                
                if ($currentAgent) {
                    $agents->prepend($currentAgent);
                }
            }
        }
        
        // Prepare data for the view
        $agents = $agents->map(function($agent) {
            $converted = $agent->converted_leads_count ?? 0;
            $pending = $agent->pending_leads_count ?? 0;
            $rejected = $agent->rejected_leads_count ?? 0;
            $total = $converted + $pending + $rejected;
            
            $conversionRate = $total > 0 ? round(($converted / $total) * 100, 2) : 0;
            
            return (object)[
                'id' => $agent->id,
                'emp_name' => $agent->emp_name,
                'emp_email' => $agent->emp_email,
                'emp_phone' => $agent->emp_phone,
                'total_leads' => $total,
                'converted' => $converted,
                'pending' => $pending,
                'rejected' => $rejected,
                'conversion_rate' => $conversionRate
            ];
        });
        
        // Prepare data for charts
        $chartData = [
            'labels' => $agents->pluck('emp_name')->toArray(),
            'conversionRates' => $agents->pluck('conversion_rate')->toArray(),
            'converted' => $agents->pluck('converted')->toArray(),
            'pending' => $agents->pluck('pending')->toArray(),
            'rejected' => $agents->pluck('rejected')->toArray(),
        ];
        
        return view('team_management.agent_referral_leads', [
            'agents' => $agents,
            'chartData' => $chartData,
        ]);
    }
    
    /**
     * Get the display name for a role ID
     */
    private function getRoleName($roleId)
    {
        $roles = [
            1 => 'SuperAdmin',
            2 => 'Agent',
            4 => 'HR',
            5 => 'Accountant',
            6 => 'Team Leader',
            7 => 'Referral Agent'
        ];
        
        return $roles[$roleId] ?? 'Unknown Role';
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

        // For both admins and team leaders, show all agents
        $query = Employee::whereIn('emp_job_role', [2, 7]); // Both regular agents (2) and chain team agents (7)
        
        // If not admin, filter by team if needed
        if (!$isAdmin) {
            $query->where(function($q) use ($teamLeaderId) {
                $q->where('reports_to', $teamLeaderId)
                  ->orWhere('id', $teamLeaderId); // Include team leader in their own team view
            });
        }
        
        $teamMembers = $query->with(['reportsTo' => function($q) {
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
            ->orderBy('emp_job_role') // Group by role
            ->orderBy('emp_name')     // Then sort by name
            ->get();
            
        return view('team_management.index', compact('teamMembers', 'leadStatuses', 'isAdmin'));
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

        // Get all agents (role 2) with their team leader info
        $agents = Employee::where('emp_job_role', 2)
            ->with(['reportsTo' => function($query) {
                $query->select('id', 'emp_name');
            }])
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
        // Only allow admins (1), team leaders (2), and chain team agents (7) to access
        if (!in_array(session('emp_job_role'), [1, 2, 7])) {
            abort(403, 'Unauthorized access.');
        }

        $currentUser = Auth::user();
        
        // Get all agents in the system with their referrals preloaded
        // Separate regular agents (2) and chain team agents (7)
        $isChainTeamAgent = $currentUser->emp_job_role == 7;
        $roleToShow = $isChainTeamAgent ? 7 : 2;
        
        // Get all agents of the same role type (2 or 7)
        $allAgents = Employee::where('emp_job_role', $roleToShow)
            ->with(['referrals' => function($query) use ($roleToShow) {
                $query->where('emp_job_role', $roleToShow);
            }])
            ->get()
            ->keyBy('id');
        
        // Build a map of referrer_id to their direct referrals
        $referralMap = [];
        foreach ($allAgents as $agent) {
            if (!isset($referralMap[$agent->referrer_id])) {
                $referralMap[$agent->referrer_id] = [];
            }
            $referralMap[$agent->referrer_id][] = $agent;
            $agent->direct_referrals_count = 0; // Initialize count
        }
        
        // Update direct referrals count
        foreach ($allAgents as $agent) {
            if (isset($referralMap[$agent->id])) {
                $agent->direct_referrals_count = count($referralMap[$agent->id]);
                $agent->setRelation('referrals', collect($referralMap[$agent->id]));
            } else {
                $agent->direct_referrals_count = 0;
                $agent->setRelation('referrals', collect());
            }
        }
        
        // Build the full referral tree
        $referralTree = [];
        $processedAgents = [];
        
        if ($currentUser->emp_job_role == 1) { // If admin, show all agents
            // For admin, show all agents who don't have a referrer or whose referrer is not in the system
            $rootAgents = $allAgents->filter(function($agent) use ($allAgents) {
                return !$agent->referrer_id || !$allAgents->has($agent->referrer_id);
            });
        } else {
            // For non-admin users, start with the current user as the root
            $rootAgents = collect([$allAgents->get($currentUser->id) ?? $currentUser]);
        }
        
        // Build the tree starting from root agents
        foreach ($rootAgents as $agent) {
            if ($agent && !in_array($agent->id, $processedAgents)) {
                $referralTree[] = $this->buildReferralTree($agent, $allAgents, $processedAgents);
                $processedAgents[] = $agent->id;
            }
        }

        return view('team.referral-chain', [
            'referralTree' => $referralTree,
            'currentUser' => $currentUser,
            'isChainTeam' => $isChainTeamAgent
        ]);
    }

    /**
     * Recursively build the referral tree
     */
    private function buildReferralTree($agent, $allAgents, &$processedAgents, $level = 0)
    {
        // Safety check - prevent infinite recursion
        if ($level > 20) {
            $agent->setRelation('referrals', collect());
            return $agent;
        }
        
        // Mark this agent as processed if not already
        if (!in_array($agent->id, $processedAgents)) {
            $processedAgents[] = $agent->id;
        }

        // Process all direct referrals
        $processedReferrals = collect();
        foreach ($agent->referrals as $referral) {
            if (!in_array($referral->id, $processedAgents)) {
                // Make sure we have the full agent object
                $fullReferral = $allAgents->get($referral->id) ?? $referral;
                $processedReferrals->push($this->buildReferralTree(
                    $fullReferral, 
                    $allAgents, 
                    $processedAgents, 
                    $level + 1
                ));
            }
        }
        
        // Set the processed referrals relationship
        $agent->setRelation('referrals', $processedReferrals);
        
        // Calculate total team size (direct + indirect referrals)
        $agent->team_size = $processedReferrals->sum('team_size') + $processedReferrals->count();
        
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
