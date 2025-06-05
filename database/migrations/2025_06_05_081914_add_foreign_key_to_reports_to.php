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
        // First, ensure the reports_to column exists and is the correct type
        if (!Schema::hasColumn('employees', 'reports_to')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->unsignedBigInteger('reports_to')->nullable()->after('emp_job_role');
            });
        } else {
            // Make sure the column is nullable and unsigned
            DB::statement('ALTER TABLE employees MODIFY reports_to BIGINT UNSIGNED NULL');
        }
        
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
        Schema::table('reports_to', function (Blueprint $table) {
            //
        });
    }
};
