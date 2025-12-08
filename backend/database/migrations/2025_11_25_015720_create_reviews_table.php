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
        Schema::create('reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('session_id')->constrained('sessions')->onDelete('cascade');
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('therapist_id')->constrained('therapists')->onDelete('cascade');
            $table->integer('rating')->unsigned()->comment('1-5 stars');
            $table->text('comment')->nullable();
            $table->boolean('is_published')->default(false);
            $table->boolean('is_anonymous')->default(false);
            $table->text('response')->nullable()->comment('Therapist response');
            $table->timestamp('responded_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('therapist_id');
            $table->index('session_id');
            $table->index('rating');
            $table->index('is_published');

            // Unique constraint - one review per session
            $table->unique('session_id');
        });

        // Check constraint for rating (1-5 stars)
        DB::statement('ALTER TABLE reviews ADD CONSTRAINT reviews_rating_check CHECK (rating >= 1 AND rating <= 5)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
