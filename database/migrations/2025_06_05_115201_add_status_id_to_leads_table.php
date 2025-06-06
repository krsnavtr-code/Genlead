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
        Schema::table('leads', function (Blueprint $table) {
            // Add status_id column with a default value of 1 (New status)
            $table->foreignId('status_id')->default(1)->after('id')->constrained('lead_statuses');
            
            // Add index for better performance
            $table->index('status_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropForeign(['status_id']);
            
            // Then drop the column
            $table->dropColumn('status_id');
        });
    }
};
