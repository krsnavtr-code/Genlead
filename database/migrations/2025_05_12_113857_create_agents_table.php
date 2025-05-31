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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('emp_name');
            $table->string('emp_email')->unique();
            $table->string('emp_phone')->nullable();
            $table->string('emp_branch')->nullable();
            $table->string('emp_location')->nullable();
            $table->string('emp_salary')->nullable();
            $table->string('emp_pic')->nullable();
            $table->date('emp_join_date')->nullable();
            $table->string('emp_username');
            $table->string('emp_password');
            $table->integer('emp_job_role');
            $table->timestamps();
            $table->integer('referrer_id')->nullable();
            $table->string('referral_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
