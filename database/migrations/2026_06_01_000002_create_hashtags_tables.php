<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Master hashtag registry
        Schema::create('hashtags', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();    // 'technology'
            $table->string('slug')->unique();    // 'technology' (lowercase, no spaces)
            $table->unsignedInteger('usage_count')->default(0); // for popular sorting
            $table->timestamps();
        });

        // Many-to-many pivot: post ↔ hashtag
        Schema::create('post_hashtags', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('hashtag_id')->constrained()->cascadeOnDelete();
            $table->primary(['post_id', 'hashtag_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('post_hashtags');
        Schema::dropIfExists('hashtags');
    }
};
