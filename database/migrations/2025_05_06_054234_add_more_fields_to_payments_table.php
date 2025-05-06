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
        Schema::table('payments', function (Blueprint $table) {
            $table->date('payment_date')->after('payment_amount')->nullable();
            $table->string('reference_number')->after('utr_no')->nullable();
            $table->enum('status', ['pending', 'verified', 'rejected'])->default('pending')->after('payment_amount');
            $table->unsignedBigInteger('verified_by')->nullable()->after('status');
            $table->timestamp('verified_at')->nullable()->after('verified_by');
            $table->text('notes')->nullable()->after('verified_at');
            
            // Add foreign key constraint for verified_by
            $table->foreign('verified_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            
            $table->dropColumn([
                'payment_date',
                'reference_number',
                'status',
                'verified_by',
                'verified_at',
                'notes'
            ]);
        });
    }
};
