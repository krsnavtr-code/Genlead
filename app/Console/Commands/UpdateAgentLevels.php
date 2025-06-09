<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\AgentLevelService;
use App\Models\Employee;

class UpdateAgentLevels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'agent:levels:update {--id= : Update a specific agent by ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update agent levels based on team size';

    /**
     * The AgentLevelService instance.
     *
     * @var AgentLevelService
     */
    protected $agentLevelService;

    /**
     * Create a new command instance.
     *
     * @param AgentLevelService $agentLevelService
     * @return void
     */
    public function __construct(AgentLevelService $agentLevelService)
    {
        parent::__construct();
        $this->agentLevelService = $agentLevelService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $agentId = $this->option('id');
        
        if ($agentId) {
            // Update specific agent by ID
            try {
                $this->updateSingleAgent($agentId);
            } catch (\InvalidArgumentException $e) {
                $this->error($e->getMessage());
                return 1;
            }
        } else {
            // Update all agents
            $agents = Employee::where('emp_job_role', 7)->get();
            
            if ($agents->isEmpty()) {
                $this->info('No chain team agents found.');
                return 0;
            }
            
            $this->info("Updating levels for {$agents->count()} agents...");
            $bar = $this->output->createProgressBar($agents->count());
            
            foreach ($agents as $agent) {
                $this->updateSingleAgent($agent);
                $bar->advance();
            }
            
            $bar->finish();
            $this->newLine(2);
            $this->info('All agent levels have been updated successfully!');
        }
        
        return 0;
    }
    
    /**
     * Update a single agent's level and display the result.
     *
     * @param \App\Models\Employee|int $agent Agent instance or agent ID
     * @return void
     * @throws \InvalidArgumentException If agent is not found or invalid
     */
    protected function updateSingleAgent($agent)
    {
        // If an ID is provided, fetch the agent
        if (is_numeric($agent)) {
            $agentId = $agent; // Store the ID before reassignment
            $agent = Employee::where('emp_job_role', 7)->find($agentId);
            if (!$agent) {
                throw new \InvalidArgumentException("Agent with ID {$agentId} not found or not a chain team agent.");
            }
        }

        // Ensure we have a valid Employee instance
        if (!($agent instanceof Employee)) {
            throw new \InvalidArgumentException('Invalid agent provided. Expected an Employee instance or ID.');
        }
        $oldLevel = $agent->agent_level;
        $oldCommission = $agent->commission_rate;
        
        $updated = $this->agentLevelService->updateAgentLevel($agent);
        
        // Refresh the agent to get updated values
        $agent->refresh();
        
        if ($updated) {
            $this->info(sprintf(
                'Agent #%d: %s - Level updated from %s to %s, Commission: ₹%s (Team Size: %d)',
                $agent->id,
                $agent->emp_name,
                $oldLevel ?: 'N/A',
                $agent->agent_level,
                number_format($agent->commission_rate, 2),
                $agent->team_size
            ));
        } else {
            $this->line(sprintf(
                'Agent #%d: %s - No level change (Level: %s, Commission: ₹%s, Team Size: %d)',
                $agent->id,
                $agent->emp_name,
                $agent->agent_level,
                number_format($agent->commission_rate, 2),
                $agent->team_size
            ), null, 'v');
        }
    }
}
