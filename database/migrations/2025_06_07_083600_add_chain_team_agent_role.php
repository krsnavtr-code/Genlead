<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddChainTeamAgentRole extends Migration
{
    public function up()
    {
        // Check if the role already exists
        $existingRole = DB::table('jobroles')->where('id', 7)->first();
        
        if (!$existingRole) {
            // First, check if the columns exist
            $hasTimestamps = Schema::hasColumns('jobroles', ['created_at', 'updated_at']);
            
            $data = [
                'id' => 7,
                'job_role_title' => 'Chain Team Agent',
                'permissions' => json_encode([
                    // Add appropriate permissions here based on your requirements
                    // These should be similar to agent permissions but tailored for chain team
                    "1", "2", "3", "4", "5", "6", "7", "16", "17", "18", "19", "70", "58", "59"
                ])
            ];
            
            // Add timestamps only if the columns exist
            if ($hasTimestamps) {
                $data['created_at'] = now();
                $data['updated_at'] = now();
            }
            
            DB::table('jobroles')->insert($data);
        }
    }

    public function down()
    {
        // Optional: Remove the role if needed
        // DB::table('jobroles')->where('id', 7)->delete();
    }
}
