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
        Schema::create('match_results', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('assessment_result_id')->nullable()->constrained('assessment_results')->onDelete('set null');
            $table->foreignUuid('therapist_id')->constrained('therapists')->onDelete('cascade');
            $table->decimal('match_score', 5, 2)->comment('Match score 0-100');
            $table->integer('rank')->unsigned()->comment('Rank 1-5');
            $table->json('matching_factors')->nullable()->comment('Specialization, availability, etc.');
            $table->boolean('is_viewed')->default(false);
            $table->timestamp('viewed_at')->nullable();
            $table->boolean('is_contacted')->default(false);
            $table->timestamp('contacted_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('therapist_id');
            $table->index('assessment_result_id');
            $table->index('rank');
            $table->index('match_score');
            $table->index(['user_id', 'rank']);
            $table->index(['user_id', 'is_viewed']);
        });

        // Check constraints using raw SQL
        DB::statement('ALTER TABLE match_results ADD CONSTRAINT match_results_score_check CHECK (match_score >= 0 AND match_score <= 100)');
        DB::statement('ALTER TABLE match_results ADD CONSTRAINT match_results_rank_check CHECK (rank >= 1 AND rank <= 5)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_results');
    }
};
