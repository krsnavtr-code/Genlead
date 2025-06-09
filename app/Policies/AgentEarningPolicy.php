<?php

namespace App\Policies;

use App\Models\AgentEarning;
use App\Models\personal\Agent;
use Illuminate\Auth\Access\HandlesAuthorization;

class AgentEarningPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the agent can view any earnings.
     */
    public function viewAny(Agent $agent): bool
    {
        return true; // All authenticated agents can view their own earnings
    }

    /**
     * Determine whether the agent can view the earning.
     */
    public function view(Agent $agent, AgentEarning $earning): bool
    {
        return $agent->isAdmin() || $agent->id === $earning->agent_id;
    }

    /**
     * Determine whether the agent can create earnings.
     */
    public function create(Agent $agent): bool
    {
        return $agent->isAdmin();
    }

    /**
     * Determine whether the agent can update the earning.
     */
    public function update(Agent $agent, AgentEarning $earning): bool
    {
        return $agent->isAdmin();
    }

    /**
     * Determine whether the agent can delete the earning.
     */
    public function delete(Agent $agent, AgentEarning $earning): bool
    {
        return $agent->isAdmin();
    }
    
    /**
     * Determine whether the agent can process payouts for all earnings of an agent.
     */
    public function payoutAll(Agent $agent, Agent $targetAgent): bool
    {
        return $agent->isAdmin() || $agent->id === $targetAgent->id;
    }
}