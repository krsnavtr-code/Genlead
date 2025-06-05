<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddTeamLeaderRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add Team Leader role (ID 6)
        if (!DB::table('jobroles')->where('job_role_title', 'Team Leader')->exists()) {
            // Using permissions similar to agent but with additional team management capabilities
            DB::table('jobroles')->insert([
                'id' => 6, // Next available ID after 5
                'job_role_title' => 'Team Leader',
                'permissions' => json_encode([
                    "1", "2", "3", "4", "5", "6", "7", "16", "17", "18", "19", // Agent permissions
                    "70", "58", "59" // Additional permissions from Accountant
                ])
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove the Team Leader role
        DB::table('jobroles')->where('job_role_title', 'Team Leader')->delete();
    }
}
