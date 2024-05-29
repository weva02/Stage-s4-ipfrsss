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
        // Supprimer la table si elle existe déjà
        Schema::dropIfExists('contenus_formations');
        
        // Créer la table
        Schema::create('contenus_formations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('formation_id');  // Clé étrangère vers la table formations
            $table->integer('NumChap');
            $table->integer('NumUnite');
            $table->text('description');
            $table->integer('NombreHeures');
    
            $table->timestamps();
    
            // Définir la contrainte de clé étrangère
            $table->foreign('formation_id')->references('id')->on('formations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contenus_formation');
    }
};
