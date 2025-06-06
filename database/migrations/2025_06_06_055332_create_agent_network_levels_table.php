<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, ensure the employees table has the correct structure
        if (!Schema::hasTable('employees')) {
            Schema::create('employees', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
            });
        }

        Schema::create('agent_network_levels', function (Blueprint $table) {
            $table->id();
            
            // Use signed bigInteger to match the employees.id type
            $table->bigInteger('agent_id');
            $table->bigInteger('referral_id');
            $table->unsignedInteger('level');
            $table->timestamps();

            // Add the foreign key constraints with explicit naming
            $table->foreign('agent_id', 'fk_agent_network_agent_id')
                  ->references('id')
                  ->on('employees')
                  ->onDelete('cascade');
                  
            $table->foreign('referral_id', 'fk_agent_network_referral_id')
                  ->references('id')
                  ->on('employees')
                  ->onDelete('cascade');
            
            // Add unique constraint
            $table->unique(['agent_id', 'referral_id'], 'agent_referral_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agent_network_levels');
    }
};
