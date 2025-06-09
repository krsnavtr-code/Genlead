<?php

namespace App\Services;

use App\Models\Employee;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AgentLevelService
{
    /**
     * Agent level thresholds and commission rates
     */
    private const LEVEL_THRESHOLDS = [
        ['min' => 500, 'commission' => 10000, 'level' => 'Platinum'],
        ['min' => 50,  'commission' => 5000,  'level' => 'Diamond'],
        ['min' => 25,  'commission' => 3500,  'level' => 'Gold'],
        ['min' => 10,  'commission' => 3000,  'level' => 'Silver'],
        ['min' => 1,   'commission' => 2500,  'level' => 'Bronze'],
        ['min' => 0,   'commission' => 0,     'level' => 'General'],
    ];

    /**
     * Update an agent's level based on their team size
     * 
     * @param \App\Models\Employee $agent
     * @return bool Whether the agent's level was updated
     */
    public function updateAgentLevel(Employee $agent): bool
    {
        if ($agent->emp_job_role !== 7) {
            return false;
        }

        try {
            // Get the current team size
            $teamSize = $agent->getTeamSize();
            
            // Log the calculation for debugging
            Log::info("Agent {$agent->id} team size calculation", [
                'agent_id' => $agent->id,
                'referrer_id' => $agent->referrer_id,
                'current_team_size' => $agent->team_size,
                'calculated_team_size' => $teamSize,
                'current_level' => $agent->agent_level,
            ]);
            
            // Find the appropriate level for the team size
            $level = $this->getLevelForTeamSize($teamSize);
            
            // Check if the level has changed or if team size needs to be updated
            if ($agent->agent_level !== $level['level'] || 
                $agent->commission_rate != $level['commission'] ||
                $agent->team_size != $teamSize) {
                
                // Update the agent's level and commission
                $agent->agent_level = $level['level'];
                $agent->commission_rate = $level['commission'];
                $agent->team_size = $teamSize;
                $agent->last_level_updated_at = now();
                
                $saved = $agent->save();
                
                if ($saved) {
                    Log::info("Agent {$agent->id} level updated", [
                        'agent_id' => $agent->id,
                        'old_level' => $agent->getOriginal('agent_level'),
                        'new_level' => $level['level'],
                        'old_commission' => $agent->getOriginal('commission_rate'),
                        'new_commission' => $level['commission'],
                        'team_size' => $teamSize,
                    ]);
                }
                
                return $saved;
            }
            
            return false;
            
        } catch (\Exception $e) {
            Log::error("Error updating agent level", [
                'agent_id' => $agent->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return false;
        }
    }

    /**
     * Update all agents' levels
     */
    public function updateAllAgents()
    {
        $agents = Employee::where('emp_job_role', 7)->get();
        
        foreach ($agents as $agent) {
            $this->updateAgentLevel($agent);
        }
    }

    /**
     * Get the commission rate for a given team size
     *
     * @param int $teamSize
     * @return float
     */
    public static function getCommissionRateForTeamSize(int $teamSize): float
    {
        foreach (self::LEVEL_THRESHOLDS as $threshold) {
            if ($teamSize >= $threshold['min']) {
                return $threshold['commission'];
            }
        }
        
        return 0;
    }

    /**
     * Get the appropriate level for a given team size
     *
     * @param int $teamSize
     * @return array
     */
    public function getLevelForTeamSize(int $teamSize): array
    {
        // Sort thresholds in descending order of min team size
        $thresholds = collect(self::LEVEL_THRESHOLDS)
            ->sortByDesc('min');

        foreach ($thresholds as $threshold) {
            if ($teamSize >= $threshold['min']) {
                return [
                    'level' => $threshold['level'],
                    'commission' => $threshold['commission'],
                ];
            }
        }

        // Default to General level
        return [
            'level' => 'General',
            'commission' => 0,
        ];
    }
    
    /**
     * Get all level thresholds
     * 
     * @return array
     */
    public function getLevelThresholds(): array
    {
        return self::LEVEL_THRESHOLDS;
    }
}
