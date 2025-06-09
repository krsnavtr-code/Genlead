<?php

namespace App\Console\Commands;

use App\Models\Employee;
use App\Services\AgentLevelService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestAgentLevels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:agent-levels {--id= : Test a specific agent ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test agent levels calculation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $agentId = $this->option('id');

        if ($agentId) {
            $this->testSingleAgent($agentId);
        } else {
            $this->testAllAgents();
        }
    }

    protected function testSingleAgent($agentId)
    {
        $agent = Employee::find($agentId);

        if (!$agent) {
            $this->error("Agent with ID {$agentId} not found.");
            return;
        }

        $this->info("Testing Agent: {$agent->emp_name} (ID: {$agent->id})");
        $this->line("Current Level: {$agent->agent_level}");
        $this->line("Current Commission: ₹{$agent->commission_rate}");
        $this->line("Current Team Size: {$agent->team_size}");

        // Display team hierarchy
        $this->info("\nTeam Hierarchy:");
        $this->displayTeamHierarchy($agent->id);

        // Calculate team size
        $teamSize = $agent->getTeamSize();
        $this->info("\nCalculated Bottom-Level Team Size: {$teamSize}");

        // Get agent level info
        $levelInfo = $agent->getAgentLevel();
        $this->info("\nAgent Level Info:");
        $this->line("- Level: {$levelInfo['level']}");
        $this->line("- Commission: ₹{$levelInfo['commission']}");
        $this->line("- Team Size: {$levelInfo['team_size']}");

        // Test updating the agent level
        $service = new AgentLevelService();
        
        // Ensure we're passing an Employee instance, not a collection
        if ($agent instanceof \Illuminate\Database\Eloquent\Collection) {
            $agent = $agent->first();
        }
        
        if (!$agent) {
            $this->error('No agent found.');
            return;
        }
        
        $updated = $service->updateAgentLevel($agent);
        
        $agent->refresh();
        
        $this->info("\nAfter Update:");
        $this->line("Level: {$agent->agent_level}");
        $this->line("Commission: ₹{$agent->commission_rate}");
        $this->line("Team Size: {$agent->team_size}");
    }
    
    /**
     * Display the team hierarchy for an agent
     */
    protected function displayTeamHierarchy($agentId, $level = 0, &$displayed = [])
    {
        // Prevent infinite recursion
        if (in_array($agentId, $displayed)) {
            return;
        }
        
        $displayed[] = $agentId;
        
        // Get the agent
        $agent = Employee::find($agentId);
        if (!$agent) {
            return;
        }
        
        // Display the agent
        $prefix = str_repeat('  ', $level * 2);
        $hasReferrals = Employee::where('referrer_id', $agentId)
            ->where('emp_job_role', 7)
            ->exists();
            
        $status = $hasReferrals ? '' : ' (Bottom Level)';
        $this->line("{$prefix}- {$agent->emp_name} [ID: {$agent->id}]{$status}");
        
        // Get and display direct referrals
        $referrals = Employee::where('referrer_id', $agentId)
            ->where('emp_job_role', 7)
            ->orderBy('id')
            ->get();
            
        foreach ($referrals as $referral) {
            $this->displayTeamHierarchy($referral->id, $level + 1, $displayed);
        }
    }

    protected function testAllAgents()
    {
        $agents = Employee::where('emp_job_role', 7)->get();

        if ($agents->isEmpty()) {
            $this->warn("No chain team agents found.");
            return;
        }

        $this->info("Found {$agents->count()} chain team agents.");

        $headers = ['ID', 'Name', 'Referrer ID', 'Current Level', 'Current Commission', 'Current Team Size', 'Calculated Team Size'];
        $rows = [];

        foreach ($agents as $agent) {
            $teamSize = $agent->getTeamSize();
            
            $rows[] = [
                $agent->id,
                $agent->emp_name,
                $agent->referrer_id ?: 'None',
                $agent->agent_level ?: 'N/A',
                '₹' . number_format($agent->commission_rate, 2),
                $agent->team_size,
                $teamSize,
            ];
        }

        $this->table($headers, $rows);
    }
}
