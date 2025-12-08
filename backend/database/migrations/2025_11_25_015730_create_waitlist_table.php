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
        Schema::create('waitlist', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('therapist_id')->constrained('therapists')->onDelete('cascade');
            $table->date('preferred_date')->nullable();
            $table->time('preferred_time')->nullable();
            $table->enum('session_type', ['individual', 'group', 'consultation'])->default('individual');
            $table->enum('status', ['pending', 'notified', 'booked', 'expired', 'cancelled'])->default('pending');
            $table->timestamp('notified_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('therapist_id');
            $table->index('status');
            $table->index('preferred_date');
            $table->index(['therapist_id', 'status']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waitlist');
    }
};
