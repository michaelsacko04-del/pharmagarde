<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gardes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pharmacie_id')->constrained('pharmacies')->cascadeOnDelete();
            $table->date('debut');
            $table->date('fin');
            $table->timestamps();

            $table->index(['debut', 'fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gardes');
    }
};
