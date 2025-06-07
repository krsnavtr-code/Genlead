<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->hasMany(Employee::class, 'referrer_id')
            ->when($this->emp_job_role == 7, function($query) {
                // If this is a Chain Team Agent, only show Chain Team Agent referrals
                return $query->where('emp_job_role', 7);
            }, function($query) {
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

    /**
     * Get the team members that report to this employee (for team leaders)
     */
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
        return $query->where('emp_job_role', 6); // Role ID for Team Leader
    }

    /**
     * Scope a query to only include team members (agents)
     */
    public function scopeTeamAgents($query)
    {
        return $query->where('emp_job_role', 2); // Role ID for Agent
    }
    
    /**
     * Scope a query to only include chain team agents
     */
    public function scopeChainTeamAgents($query)
    {
        return $query->where('emp_job_role', 7); // Role ID for Chain Team Agent
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
