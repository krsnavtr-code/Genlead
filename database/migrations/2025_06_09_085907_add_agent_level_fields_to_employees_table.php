<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->string('agent_level')->nullable()->after('referral_code')->comment('Agent level: General, Bronze, Silver, Gold, Diamond, Platinum');
            $table->decimal('commission_rate', 10, 2)->default(0)->after('agent_level')->comment('Commission rate per admission in INR');
            $table->integer('team_size')->default(0)->after('commission_rate')->comment('Number of team members in downline');
            $table->timestamp('last_level_updated_at')->nullable()->after('team_size')->comment('When the agent level was last updated');
        });

        // Update existing role 7 agents with their initial levels
        DB::table('employees')
            ->where('emp_job_role', 7)
            ->update([
                'agent_level' => 'General',
                'commission_rate' => 0,
                'last_level_updated_at' => now(),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['agent_level', 'commission_rate', 'team_size', 'last_level_updated_at']);
        });
    }
};
