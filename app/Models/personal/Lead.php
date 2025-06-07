<?php

namespace App\Models\personal;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use App\Models\personal\LeadStatus;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "leads";
    protected $primaryKey = 'id';

    protected $fillable = [
        'agent_id',
        'first_name',
        'last_name',
        'email',
        'email_domain',
        'phone',
        'lead_source',
        'university',
        'courses',
        'session_duration',
        'status',
        'status_id',
        'total_fees',
        'pending_amount',
        'assigned_to',
        'priority',
        'last_follow_up',
        'next_follow_up',
        'transfer_notes',
        'transferred_at',
        'transferred_by',
        'converted_at',
        'lost_reason',
        'lost_at',
    ];

    protected $casts = [
        'total_fees' => 'decimal:2',
        'pending_amount' => 'decimal:2',
        'last_follow_up' => 'datetime',
        'next_follow_up' => 'datetime',
        'transferred_at' => 'datetime',
        'converted_at' => 'datetime',
        'lost_at' => 'datetime',
    ];

    protected $appends = ['full_name'];

    /**
     * Get the agent that owns the lead
     */
    public function agent()
    {
        return $this->belongsTo(Employee::class, 'agent_id');
    }

    /**
     * Get the team lead for this lead's agent
     */
    public function teamLead()
    {
        return $this->hasOneThrough(
            Employee::class,
            Employee::class,
            'id', // Foreign key on employees table...
            'id', // Foreign key on team_leads table...
            'agent_id', // Local key on leads table...
            'reports_to' // Local key on employees table...
        );
    }

    
    /**
     * Get the status record associated with the lead
     */
    public function status()
    {
        return $this->belongsTo(LeadStatus::class, 'status_id');
    }
    
    /**
     * Get all follow-ups for the lead
     */
    public function followUps()
    {
        return $this->hasMany(FollowUp::class)->latest('follow_up_time');
    }
    
    /**
     * Get the history of the lead
     */
    public function history()
    {
        return $this->hasMany(LeadHistory::class)->latest();
    }
    
    /**
     * Get the full name of the lead
     */
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }
    
    /**
     * Scope a query to only include leads assigned to a specific agent
     */
    public function scopeAssignedTo($query, $agentId)
    {
        return $query->where('agent_id', $agentId);
    }
    
    /**
     * Scope a query to only include leads with a specific status
     */
    public function scopeWithStatus($query, $status)
    {
        if (is_array($status)) {
            return $query->whereIn('status', $status);
        }
        return $query->where('status', $status);
    }
    
    /**
     * Scope a query to only include leads created within a date range
     */
    public function scopeCreatedBetween($query, $startDate, $endDate = null)
    {
        if (is_null($endDate)) {
            $endDate = Carbon::now();
        }
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Get the payments for the lead
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'lead_id', 'id');
    }
}
