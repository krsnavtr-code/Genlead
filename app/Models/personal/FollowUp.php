<?php

namespace App\Models\personal;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class FollowUp extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'follow_ups';
    protected $primaryKey = 'id';

    protected $fillable = [
        'lead_id',
        'agent_id',
        'follow_up_time',
        'comments',
        'action',
        'notified',
        'status',
        'outcome',
        'reminder_sent',
        'reminder_sent_at',
        'created_by'
    ];

    protected $casts = [
        'follow_up_time' => 'datetime',
        'notified' => 'boolean',
        'reminder_sent' => 'boolean',
        'reminder_sent_at' => 'datetime',
    ];

    protected $appends = ['status_label', 'is_past_due'];
    
    /**
     * Get the lead that this follow-up belongs to
     */
    public function lead()
    {
        return $this->belongsTo(Lead::class, 'lead_id');
    }
    
    /**
     * Get the agent who is responsible for this follow-up
     */
    public function agent()
    {
        return $this->belongsTo(Employee::class, 'agent_id');
    }
    
    /**
     * Get the user who created this follow-up
     */
    public function createdBy()
    {
        return $this->belongsTo(Employee::class, 'created_by');
    }
    
    /**
     * Scope a query to only include upcoming follow-ups
     */
    public function scopeUpcoming($query)
    {
        return $query->where('follow_up_time', '>=', now())
                    ->orderBy('follow_up_time', 'asc');
    }
    
    /**
     * Scope a query to only include past due follow-ups
     */
    public function scopePastDue($query)
    {
        return $query->where('follow_up_time', '<', now())
                    ->where(function($q) {
                        $q->where('status', '!=', 'completed')
                          ->orWhereNull('status');
                    })
                    ->orderBy('follow_up_time', 'desc');
    }
    
    /**
     * Scope a query to only include completed follow-ups
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed')
                    ->orderBy('follow_up_time', 'desc');
    }
    
    /**
     * Get the status label with appropriate color
     */
    public function getStatusLabelAttribute()
    {
        switch ($this->status) {
            case 'scheduled':
                return '<span class="badge badge-info">Scheduled</span>';
            case 'completed':
                return '<span class="badge badge-success">Completed</span>';
            case 'cancelled':
                return '<span class="badge badge-secondary">Cancelled</span>';
            case 'no_show':
                return '<span class="badge badge-warning">No Show</span>';
            case 'rescheduled':
                return '<span class="badge badge-primary">Rescheduled</span>';
            default:
                return '<span class="badge badge-light">Pending</span>';
        }
    }
    
    /**
     * Check if the follow-up is past due
     */
    public function getIsPastDueAttribute()
    {
        return $this->follow_up_time && $this->follow_up_time->isPast() 
            && $this->status !== 'completed' 
            && $this->status !== 'cancelled';
    }
    
    /**
     * Mark the follow-up as completed
     */
    public function markAsCompleted($outcome = null, $notes = null)
    {
        $this->update([
            'status' => 'completed',
            'outcome' => $outcome,
            'comments' => $notes ?: $this->comments,
            'completed_at' => now()
        ]);
        
        // Update the lead's last follow-up timestamp
        if ($this->lead) {
            $this->lead->update([
                'last_follow_up' => now()
            ]);
        }
        
        return $this;
    }
}
