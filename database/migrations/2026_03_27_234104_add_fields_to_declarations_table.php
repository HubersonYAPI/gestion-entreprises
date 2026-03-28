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
        Schema::table('declarations', function (Blueprint $table) {
            $table->string('nature_activite')->nullable();
            $table->string('secteur_activite')->nullable();
            $table->text('produits')->nullable();
            $table->integer('effectifs')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('declarations', function (Blueprint $table) {
            $table->dropColumn([
            'nature_activite',
            'secteur_activite',
            'produits',
            'effectifs'
            ]);
        });
    }
};
