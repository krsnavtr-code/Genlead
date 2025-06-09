<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Employee extends Model
{
    use HasFactory;

    protected $table = 'employees';

    protected $fillable = [
        'emp_name',
        'emp_email',
        'emp_phone',
        'emp_branch',
        'emp_location',
        'emp_salary',
        'emp_pic',
        'emp_join_date',
        'emp_username',
        'emp_password',
        'emp_job_role',
        'reports_to',
        'referrer_id',
        'referral_code',
        'created_at',
        'updated_at'
    ];

    protected $dates = [
        'emp_join_date',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'emp_password',
    ];

    public function jobRole()
    {
        return $this->belongsTo(JobRole::class, 'emp_job_role');
    }

    /**
     * Get all direct referrals (level 1 team members)
     * Only includes chain team agents (role 7)
     */
    public function directReferrals()
    {
        return $this->hasMany(Employee::class, 'referrer_id')
            ->where('emp_job_role', 7); // Only include chain team agents
    }

    /**
     * Get all team members in the agent's downline (recursively)
     * Only includes chain team agents (role 7)
     */
    public function getDownlineMembers()
    {
        return $this
            ->hasMany(Employee::class, 'referrer_id')
            ->where('emp_job_role', 7) // Only include chain team agents
            ->with('getDownlineMembers');
    }

    /**
     * Get the agent's level based on team size
     *
     * @return array [level_name, commission, team_size]
     */
    public function getAgentLevel()
    {
        $teamSize = $this->getTeamSize();

        if ($teamSize >= 500) {
            return [
                'level' => 'Platinum',
                'commission' => 10000,
                'team_size' => $teamSize
            ];
        } elseif ($teamSize >= 50) {
            return [
                'level' => 'Diamond',
                'commission' => 5000,
                'team_size' => $teamSize
            ];
        } elseif ($teamSize >= 25) {
            return [
                'level' => 'Gold',
                'commission' => 3500,
                'team_size' => $teamSize
            ];
        } elseif ($teamSize >= 10) {
            return [
                'level' => 'Silver',
                'commission' => 3000,
                'team_size' => $teamSize
            ];
        } elseif ($teamSize >= 1) {
            return [
                'level' => 'Bronze',
                'commission' => 2500,
                'team_size' => $teamSize
            ];
        }

        return [
            'level' => 'General',
            'commission' => 0,
            'team_size' => $teamSize
        ];
    }

    /**
     * Get the total number of team members in the agent's downline
     * This counts all agents who were referred by this agent or by someone in their downline
     */
    public function getTeamSize()
    {
        // First, try to use a more efficient query if possible
        try {
            // Get all direct and indirect referrals (team members)
            $result = DB::select("
                WITH RECURSIVE agent_tree AS (
                    -- Base case: direct referrals
                    SELECT id, referrer_id, 1 as level 
                    FROM employees 
                    WHERE referrer_id = ?
                    
                    UNION ALL
                    
                    -- Recursive case: team members of team members
                    SELECT e.id, e.referrer_id, at.level + 1
                    FROM employees e
                    INNER JOIN agent_tree at ON e.referrer_id = at.id
                    WHERE e.emp_job_role = 7  -- Only count chain team agents
                )
                SELECT COUNT(*) as total FROM agent_tree
            ", [$this->id]);
            
            return (int)($result[0]->total ?? 0);
            
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error in getTeamSize for agent ' . $this->id . ': ' . $e->getMessage());
            
            // Fall back to the recursive method
            return $this->getTeamSizeRecursive();
        }
    }

    /**
     * Fallback recursive method to count team members
     * This is used when the database doesn't support recursive CTE
     */
    protected function getTeamSizeRecursive()
    {
        $count = 0;
        // Only count direct referrals that are chain team agents (role 7)
        $directReferrals = $this->directReferrals()
            ->where('emp_job_role', 7)  // Only count chain team agents
            ->get();
        
        foreach ($directReferrals as $referral) {
            $count++; // Count the direct referral
            $count += $referral->getTeamSizeRecursive(); // Recursively count their team
        }
        
        return $count;
    }

    /**
     * Get the team leader this employee reports to
     */
    public function reportsTo()
    {
        return $this->belongsTo(Employee::class, 'reports_to');
    }

    /**
     * Get all team members that report to this employee
     */
    public function teamMembers()
    {
        return $this->hasMany(Employee::class, 'reports_to');
    }

    /**
     * Get all leads assigned to this employee
     */
    public function leads()
    {
        return $this->hasMany(\App\Models\personal\Lead::class, 'agent_id');
    }

    public function referrer()
    {
        return $this->belongsTo(Employee::class, 'referrer_id');
    }

    public function referrals()
    {
        return $this
            ->hasMany(Employee::class, 'referrer_id')
            ->when($this->emp_job_role == 7, function ($query) {
                // If this is a Chain Team Agent, only show Chain Team Agent referrals
                return $query->where('emp_job_role', 7);
            }, function ($query) {
                // Otherwise, only show regular Agent referrals
                return $query->where('emp_job_role', 2);
            });
    }

    public function getAllDescendants()
    {
        $descendants = collect([$this]);

        foreach ($this->referrals as $referral) {
            $descendants = $descendants->merge($referral->getAllDescendants());
        }

        return $descendants;
    }

    /** Get the team members that report to this employee (for team leaders) */
    // public function teamMembers()
    // {
    //     return $this->hasMany(Employee::class, 'reports_to');
    // }

    /**
     * Get the team leader this employee reports to
     */
    public function teamLeader()
    {
        return $this->belongsTo(Employee::class, 'reports_to');
    }

    /**
     * Scope a query to only include team leaders
     */
    public function scopeTeamLeaders($query)
    {
        return $query->where('emp_job_role', 6);  // Role ID for Team Leader
    }

    /**
     * Scope a query to only include team members (agents)
     */
    public function scopeTeamAgents($query)
    {
        return $query->where('emp_job_role', 2);  // Role ID for Agent
    }

    /**
     * Scope a query to only include chain team agents
     */
    public function scopeChainTeamAgents($query)
    {
        return $query->where('emp_job_role', 7);  // Role ID for Chain Team Agent
    }

    /**
     * Check if the employee is a team leader
     */
    public function isTeamLeader()
    {
        return $this->emp_job_role === 6;
    }

    /**
     * Check if the employee is a team member (agent)
     */
    public function isTeamMember()
    {
        return $this->emp_job_role === 2;
    }

    /**
     * Check if the employee is a chain team agent
     */
    public function isChainTeamAgent()
    {
        return $this->emp_job_role === 7;
    }
}
