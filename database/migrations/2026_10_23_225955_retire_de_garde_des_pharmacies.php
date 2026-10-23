<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Le statut de garde se déduit désormais de la table gardes.
    public function up(): void
    {
        Schema::table('pharmacies', function (Blueprint $table) {
            $table->dropColumn('de_garde');
        });
    }

    public function down(): void
    {
        Schema::table('pharmacies', function (Blueprint $table) {
            $table->boolean('de_garde')->default(false);
        });
    }
};
