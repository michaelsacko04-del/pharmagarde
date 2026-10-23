<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pharmacie_id')->constrained('pharmacies')->cascadeOnDelete();
            $table->foreignId('medicament_id')->constrained('medicaments')->cascadeOnDelete();
            $table->boolean('disponible')->default(false);
            $table->unsignedInteger('quantite')->nullable();
            $table->timestamps();

            $table->unique(['pharmacie_id', 'medicament_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
