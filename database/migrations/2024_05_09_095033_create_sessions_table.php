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
        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('formation_id');  // Clé étrangère vers la table formations
            $table->unsignedBigInteger('professeur_id'); // Clé étrangère vers la table professeurs
            $table->date('date_debut');
            $table->date('date_fin');
    
            $table->timestamps();
    
            // Définir les contraintes de clé étrangère
            $table->foreign('formation_id')->references('id')->on('formation')->onDelete('cascade');
            $table->foreign('professeur_id')->references('id')->on('professeurs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
