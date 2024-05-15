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
        Schema::create('etudiants', function (Blueprint $table) {
            $table->id();
            $table->integer('NNI');
            $table->string('nomprenom');
            $table->string('nationalite');
            $table->string('diplome');
            $table->string('genre');
            $table->string('lieunaissance');
            $table->string('adress');
            $table->integer('Age');
            $table->string('email', 191)->unique(); // Corrected this line
            $table->integer('phone');
            $table->integer('wtsp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etudiants');
    }
};
