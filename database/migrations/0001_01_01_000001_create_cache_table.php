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
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
<<<<<<< HEAD
            $table->bigInteger('expiration')->index();
=======
            $table->integer('expiration')->index();
>>>>>>> 14f647be00457c2be938ca3977220a2674dc60a5
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
<<<<<<< HEAD
            $table->bigInteger('expiration')->index();
=======
            $table->integer('expiration')->index();
>>>>>>> 14f647be00457c2be938ca3977220a2674dc60a5
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
    }
};
