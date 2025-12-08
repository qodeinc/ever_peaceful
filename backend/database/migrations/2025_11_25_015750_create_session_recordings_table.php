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
        Schema::create('session_recordings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('session_id')->constrained('sessions')->onDelete('cascade');
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('therapist_id')->constrained('therapists')->onDelete('cascade');
            $table->text('recording_url')->comment('Encrypted storage URL');
            $table->integer('duration_seconds')->unsigned()->default(0);
            $table->decimal('file_size_mb', 8, 2)->default(0);
            $table->boolean('consent_given')->default(false);
            $table->timestamp('consent_timestamp')->nullable();
            $table->enum('storage_provider', ['s3', 'local', 'zoom', 'jitsi'])->default('s3');
            $table->timestamp('expires_at')->nullable()->comment('Auto-delete after X days');
            $table->timestamps();

            // Indexes
            $table->index('session_id');
            $table->index('user_id');
            $table->index('therapist_id');
            $table->index('expires_at');
            $table->index('consent_given');
            $table->index(['session_id', 'consent_given']);

            // Unique constraint - one recording per session
            $table->unique('session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_recordings');
    }
};
