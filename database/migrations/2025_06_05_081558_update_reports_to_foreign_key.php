<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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
        // First, make sure the reports_to column is the correct type
        if (Schema::hasColumn('employees', 'reports_to')) {
            // Try to drop any existing foreign key constraints
            try {
                Schema::table('employees', function (Blueprint $table) {
                    $table->dropForeign(['reports_to']);
                });
            } catch (\Exception $e) {
                // Ignore if the foreign key doesn't exist
            }
            
            // Make sure the column is nullable and unsigned
            Schema::table('employees', function (Blueprint $table) {
                $table->unsignedBigInteger('reports_to')->nullable()->change();
            });
        }
        
        // Then add the foreign key constraint
        Schema::table('employees', function (Blueprint $table) {
            $table->foreign('reports_to')
                  ->references('id')
                  ->on('employees')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the foreign key constraint if it exists
        try {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropForeign(['reports_to']);
            });
        } catch (\Exception $e) {
            // Ignore if the foreign key doesn't exist
        }
    }
};
