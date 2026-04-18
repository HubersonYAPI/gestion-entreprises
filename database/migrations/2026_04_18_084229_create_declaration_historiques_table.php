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
        Schema::create('declaration_historiques', function (Blueprint $table) {
            $table->id();
            $table->foreignId('declaration_id')->constrained('declarations')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action', 50);
            $table->string('ancien_statut', 50)->nullable();
            $table->string('nouveau_statut', 50)->nullable();

            //commentaire ou motif du rejet
            $table->string('commentaire')->nullable();

            $table->string('ip_adress', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            //index pour les requêtes fréquentes
            $table->index('declaration_id');
            $table->index('user_id');
            $table->index('action');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('declaration_historiques');
    }
};
