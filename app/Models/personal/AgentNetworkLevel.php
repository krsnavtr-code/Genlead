<?php

namespace App\Models\personal;

use Illuminate\Database\Eloquent\Model;

class AgentNetworkLevel extends Model
{
    protected $table = 'agent_network_levels';
    
    protected $fillable = [
        'agent_id',
        'referral_id',
        'level'
    ];
    
    protected $casts = [
        'level' => 'integer',
    ];
    
    /**
     * Get the agent who is at the top of this network level
     */
    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }
    
    /**
     * Get the referred agent in this network level
     */
    public function referral()
    {
        return $this->belongsTo(Agent::class, 'referral_id');
    }
}
