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
        Schema::create('payouts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('therapist_id')->constrained('therapists')->onDelete('cascade');
            $table->decimal('amount', 10, 2)->comment('Total session earnings');
            $table->decimal('platform_fee', 10, 2)->comment('Platform fee (20%)');
            $table->decimal('net_amount', 10, 2)->comment('Net payout amount (80%)');
            $table->date('period_start');
            $table->date('period_end');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->enum('payment_method', ['bank_transfer', 'mobile_money', 'paypal', 'stripe_connect'])->nullable();
            $table->string('transaction_reference')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->foreignUuid('processed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('therapist_id');
            $table->index('status');
            $table->index(['therapist_id', 'status']);
            $table->index('period_start');
            $table->index('period_end');
            $table->index('processed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
