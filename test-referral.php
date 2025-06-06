<?php

use App\Models\personal\Agent;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Clear existing test data
Agent::where('emp_email', 'like', 'test%@example.com')->delete();

try {
    // Create root agent
    $rootAgent = Agent::create([
        'emp_name' => 'Test Root Agent',
        'emp_email' => 'test.root@example.com',
        'emp_username' => 'testroot',
        'emp_password' => bcrypt('password'),
        'emp_job_role' => 2,
    ]);
    
    echo "Created root agent with ID: {$rootAgent->id} and referral code: {$rootAgent->referral_code}\n";
    
    // Create level 1 referral
    $level1Agent = Agent::create([
        'emp_name' => 'Test Level 1 Agent',
        'emp_email' => 'test.level1@example.com',
        'emp_username' => 'testlevel1',
        'emp_password' => bcrypt('password'),
        'emp_job_role' => 2,
        'referrer_id' => $rootAgent->id,
    ]);
    
    echo "Created level 1 agent with ID: {$level1Agent->id} and referral code: {$level1Agent->referral_code}\n";
    
    // Create level 2 referral
    $level2Agent = Agent::create([
        'emp_name' => 'Test Level 2 Agent',
        'emp_email' => 'test.level2@example.com',
        'emp_username' => 'testlevel2',
        'emp_password' => bcrypt('password'),
        'emp_job_role' => 2,
        'referrer_id' => $level1Agent->id,
    ]);
    
    echo "Created level 2 agent with ID: {$level2Agent->id} and referral code: {$level2Agent->referral_code}\n";
    
    // Verify network levels
    echo "\nNetwork levels for root agent (ID: {$rootAgent->id}):\n";
    $network = DB::table('agent_network_levels')
        ->where('agent_id', $rootAgent->id)
        ->get();
        
    foreach ($network as $level) {
        echo "- Level {$level->level}: Agent ID {$level->referral_id}\n";
    }
    
    echo "\nNetwork levels for level 1 agent (ID: {$level1Agent->id}):\n";
    $network = DB::table('agent_network_levels')
        ->where('agent_id', $level1Agent->id)
        ->get();
        
    foreach ($network as $level) {
        echo "- Level {$level->level}: Agent ID {$level->referral_id}\n";
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
