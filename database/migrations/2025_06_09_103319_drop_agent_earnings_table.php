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
        Schema::dropIfExists('agent_earnings');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration only drops the table, so we won't implement the down method
        // as we don't want to recreate the table with potentially incorrect schema
    }
};
