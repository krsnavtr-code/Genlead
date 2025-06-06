<?php

namespace App\Console\Commands;

use App\Models\personal\Agent;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class TestReferralSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'referral:test {--levels=3 : Number of levels to test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the multi-level agent referral system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $levels = (int) $this->option('levels');
        
        // Clear existing test data
        $this->info('Clearing existing test data...');
        Agent::where('emp_email', 'like', 'test%')->delete();
        
        // Create the root agent
        $this->info('\nCreating root agent...');
        $rootAgent = Agent::create([
            'emp_name' => 'Test Root Agent',
            'emp_email' => 'test.root@example.com',
            'emp_username' => 'testroot',
            'emp_password' => Hash::make('password'),
            'emp_job_role' => 2, // Assuming 2 is the ID for agent role
        ]);
        
        $this->info("Root agent created with ID: {$rootAgent->id} and referral code: {$rootAgent->referral_code}");
        
        // Create multiple levels of referrals
        $currentAgent = $rootAgent;
        $allAgents = [$rootAgent];
        
        for ($level = 1; $level <= $levels; $level++) {
            $this->info("\nCreating level {$level} referrals...");
            $newAgents = [];
            
            foreach ($allAgents as $agent) {
                $newAgent = Agent::create([
                    'emp_name' => "Test Agent L{$level}",
                    'emp_email' => "test.agent.l{$level}." . uniqid() . "@example.com",
                    'emp_username' => 'testagent' . uniqid(),
                    'emp_password' => Hash::make('password'),
                    'emp_job_role' => 2, // Assuming 2 is the ID for agent role
                    'referrer_id' => $agent->id,
                ]);
                
                $this->info("  - Created agent ID: {$newAgent->id} (referred by: {$agent->id}) with code: {$newAgent->referral_code}");
                $newAgents[] = $newAgent;
            }
            
            $allAgents = $newAgents;
        }
        
        // Test the referral network
        $this->info("\nTesting referral network...");
        $testAgent = $rootAgent;
        $level = 1;
        
        while ($testAgent && $level <= $levels) {
            $referrals = $testAgent->networkReferrals()->get();
            $this->info("Agent {$testAgent->id} has {$referrals->count()} agents in their network");
            
            foreach ($referrals as $referral) {
                $this->info("  - Level {$referral->pivot->level}: Agent {$referral->id}");
            }
            
            $testAgent = $testAgent->referrals->first();
            $level++;
        }
        
        $this->info("\nTest completed successfully!");
    }
}
