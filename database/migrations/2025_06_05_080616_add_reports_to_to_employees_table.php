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
        // First, make sure the reports_to column exists
        if (!Schema::hasColumn('employees', 'reports_to')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->unsignedBigInteger('reports_to')->nullable()->after('emp_job_role');
            });
        }
        
        // Then add the foreign key constraint if it doesn't exist
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
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['reports_to']);
            $table->dropColumn('reports_to');
        });
    }
};
