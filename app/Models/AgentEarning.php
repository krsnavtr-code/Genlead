<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentEarning extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'agent_id',
        'amount',
        'type',
        'description',
        'reference_type',
        'reference_id',
        'earned_date',
        'is_paid',
        'paid_date',
    ];

    protected $casts = [
        'earned_date' => 'date',
        'paid_date' => 'date',
        'is_paid' => 'boolean',
        'amount' => 'decimal:2',
    ];

    /**
     * Get the agent that owns the earning.
     */
    public function agent()
    {
        return $this->belongsTo(\App\Models\personal\Agent::class, 'agent_id');
    }

    public function reference()
    {
        return $this->morphTo();
    }
}
