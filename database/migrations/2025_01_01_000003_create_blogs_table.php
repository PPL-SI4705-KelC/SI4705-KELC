<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('content')->nullable();
            $table->enum('category', ['Transportation', 'Consumption', 'Energy'])->nullable();
            $table->string('featured_image')->nullable();
            $table->string('tags')->nullable();
            $table->enum('status', ['draft', 'published', 'pending', 'rejected'])->default('draft');
            $table->text('reject_reason')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('user_id');
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
