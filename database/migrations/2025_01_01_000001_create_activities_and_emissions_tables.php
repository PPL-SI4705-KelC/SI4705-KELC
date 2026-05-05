<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Activities log (daily carbon footprint input)
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('activity_date');
            $table->json('transport_data')->nullable();
            $table->json('consumption_data')->nullable();
            $table->json('energy_data')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'activity_date']);
            $table->index('activity_date');
        });

        // Emissions calculated from activities
        Schema::create('emissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('activity_id')->constrained()->cascadeOnDelete();
            $table->decimal('transport_emission', 10, 4)->default(0);
            $table->decimal('consumption_emission', 10, 4)->default(0);
            $table->decimal('energy_emission', 10, 4)->default(0);
            $table->decimal('total_emission', 10, 4)->default(0);
            $table->decimal('sdg_score', 6, 2)->default(0);
            $table->date('emission_date');
            $table->timestamps();

            $table->index(['user_id', 'emission_date']);
            $table->index('emission_date');
        });

        // XP transaction log
        Schema::create('xp_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('xp_amount');
            $table->string('source'); // quiz, blog, achievement
            $table->string('description')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('xp_logs');
        Schema::dropIfExists('emissions');
        Schema::dropIfExists('activities');
    }
};
