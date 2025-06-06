<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Add referrer_id column if it doesn't exist
            if (!Schema::hasColumn('employees', 'referrer_id')) {
                $table->bigInteger('referrer_id')->nullable()->after('id');
                $table->foreign('referrer_id', 'fk_employees_referrer_id')
                      ->references('id')
                      ->on('employees')
                      ->onDelete('set null');
            }
            
            // Add referral_code column if it doesn't exist
            if (!Schema::hasColumn('employees', 'referral_code')) {
                $table->string('referral_code', 10)->unique()->nullable()->after('referrer_id');
            }
        });
        
        // Generate referral codes for existing agents
        $this->generateReferralCodes();
    }

    /**
     * Generate referral codes for existing agents
     */
    protected function generateReferralCodes()
    {
        $agents = \App\Models\personal\Agent::whereNull('referral_code')->get();
        
        foreach ($agents as $agent) {
            $agent->update([
                'referral_code' => \App\Models\personal\Agent::generateReferralCode()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Drop foreign key first to avoid errors
            if (Schema::hasColumn('employees', 'referrer_id')) {
                $table->dropForeign(['referrer_id']);
                $table->dropColumn('referrer_id');
            }
            
            if (Schema::hasColumn('employees', 'referral_code')) {
                $table->dropColumn('referral_code');
            }
        });
    }
};
