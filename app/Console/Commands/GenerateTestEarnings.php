<?php

namespace App\Console\Commands;

use App\Models\AgentEarning;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateTestEarnings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:test-earnings {--agent= : The ID of the agent to generate earnings for}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate test earnings data for agents';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $agentId = $this->option('agent');
        
        // If no agent ID is provided, get all chain team agents (role 7)
        $query = Employee::where('emp_job_role', 7);
        
        if ($agentId) {
            $query->where('id', $agentId);
        }
        
        $agents = $query->get();
        
        if ($agents->isEmpty()) {
            $this->error('No chain team agents found.');
            return 1;
        }
        
        $this->info('Generating test earnings for ' . $agents->count() . ' agent(s)...');
        
        $now = now();
        $earnings = [];
        
        foreach ($agents as $agent) {
            // Generate 1-5 random earnings per agent
            $count = rand(1, 5);
            
            for ($i = 0; $i < $count; $i++) {
                $amount = rand(500, 5000);
                $isPaid = (bool)rand(0, 1);
                $earnedDate = $now->copy()->subDays(rand(0, 30));
                
                $earnings[] = [
                    'agent_id' => $agent->id,
                    'amount' => $amount,
                    'type' => 'commission',
                    'description' => 'Commission for team performance',
                    'earned_date' => $earnedDate,
                    'is_paid' => $isPaid,
                    'paid_date' => $isPaid ? $earnedDate->copy()->addDays(rand(1, 7)) : null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }
        
        // Insert all earnings in a single query for better performance
        $inserted = AgentEarning::insert($earnings);
        
        $this->info('Successfully generated ' . $inserted . ' test earnings.');
        
        return 0;
    }
}
