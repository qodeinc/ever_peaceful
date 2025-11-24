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
        Schema::create('session_notes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('session_id');
            $table->uuid('therapist_id');
            $table->uuid('user_id');
            $table->text('notes');
            $table->json('goals')->nullable();
            $table->text('progress')->nullable();
            $table->text('recommendations')->nullable();
            $table->boolean('is_confidential')->default(false);
            $table->timestamps();
            
            $table->foreign('session_id')->references('id')->on('sessions')->onDelete('cascade');
            $table->foreign('therapist_id')->references('id')->on('therapists')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_notes');
    }
};
