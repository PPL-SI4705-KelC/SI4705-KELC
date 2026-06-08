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
    Schema::create('emission_records', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('activity_type'); // Contoh: Listrik, Transportasi
        $table->decimal('amount_value', 10, 2); // Nilai input (misal: 5 liter)
        $table->decimal('carbon_impact', 10, 2); // Hasil konversi (kg CO2)
        $table->date('recorded_at'); // Tanggal aktivitas untuk sumbu X di Chart.js
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('emission_records');
    }
};
