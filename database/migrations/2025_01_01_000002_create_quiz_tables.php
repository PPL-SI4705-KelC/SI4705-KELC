<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Quiz questions pool
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->json('options'); // array of 4 options
            $table->unsignedTinyInteger('correct_answer'); // 0-3 index
            $table->string('category')->default('general'); // climate, energy, etc.
            $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index('is_active');
            $table->index('category');
        });

        // Quiz attempt records
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('attempt_date');
            $table->json('question_ids'); // IDs of questions asked
            $table->json('answers'); // user's answers
            $table->unsignedTinyInteger('correct_count')->default(0);
            $table->unsignedInteger('xp_earned')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'attempt_date']);
            $table->index('attempt_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quizzes');
    }
};
