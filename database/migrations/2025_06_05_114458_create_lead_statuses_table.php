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
        Schema::create('lead_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color')->default('#6c757d');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // Insert default statuses
        DB::table('lead_statuses')->insert([
            ['name' => 'New', 'color' => '#007bff', 'sort_order' => 1],
            ['name' => 'Contacted', 'color' => '#17a2b8', 'sort_order' => 2],
            ['name' => 'Qualified', 'color' => '#28a745', 'sort_order' => 3],
            ['name' => 'Proposal Sent', 'color' => '#ffc107', 'sort_order' => 4],
            ['name' => 'Negotiation', 'color' => '#fd7e14', 'sort_order' => 5],
            ['name' => 'Converted', 'color' => '#20c997', 'sort_order' => 6],
            ['name' => 'Rejected', 'color' => '#dc3545', 'sort_order' => 7],
            ['name' => 'On Hold', 'color' => '#6f42c1', 'sort_order' => 8],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_statuses');
    }
};
