<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('agent_earnings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('agent_id');
            $table->decimal('amount', 10, 2);
            $table->string('type'); // commission, bonus, etc.
            $table->text('description')->nullable();
            $table->string('reference_type')->nullable(); // e.g., 'lead', 'referral'
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->date('earned_date');
            $table->boolean('is_paid')->default(false);
            $table->date('paid_date')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Index for better performance
            $table->index('agent_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('agent_earnings');
    }
};
