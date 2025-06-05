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
    public function up(): void
    {
        // First, drop any existing foreign key constraints on reports_to
        Schema::table('employees', function (Blueprint $table) {
            // This will drop any foreign key constraint on reports_to if it exists
            $table->dropForeign(['reports_to']);
        });
        
        // Make sure the column is the correct type
        DB::statement('ALTER TABLE employees MODIFY reports_to BIGINT UNSIGNED NULL');
        
        // Add the foreign key constraint using raw SQL to ensure it works
        DB::statement('ALTER TABLE employees 
            ADD CONSTRAINT fk_employees_reports_to 
            FOREIGN KEY (reports_to) 
            REFERENCES employees(id) 
            ON DELETE SET NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('foreign_key', function (Blueprint $table) {
            //
        });
    }
};
