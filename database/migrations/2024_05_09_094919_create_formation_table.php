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
        Schema::create('formation', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('libelle');
            $table->integer('nombre_heures');
            $table->decimal('prix', 8, 2);
            $table->unsignedBigInteger('id_domaine'); // Assurez-vous que le type correspond à celui de la clé primaire de Domaine
    
            $table->timestamps();
    
            // Contrainte de clé étrangère qui référence la table Domaine
            $table->foreign('id_domaine')->references('id')->on('domaine')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formation');
    }
};
