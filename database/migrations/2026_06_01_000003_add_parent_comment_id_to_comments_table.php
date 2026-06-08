<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // Self-referential FK: NULL = top-level comment, ID = reply to that comment
            $table->foreignId('parent_comment_id')
                  ->nullable()
                  ->after('post_id')
                  ->constrained('comments')
                  ->cascadeOnDelete();

            $table->index('parent_comment_id');
        });
    }

    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            $table->dropForeign(['parent_comment_id']);
            $table->dropIndex(['parent_comment_id']);
            $table->dropColumn('parent_comment_id');
        });
    }
};
