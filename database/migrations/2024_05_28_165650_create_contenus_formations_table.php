<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contenus_formations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('formation_id');
            $table->integer('NumChap');
            $table->integer('NumUnite');
            $table->text('description');
            $table->integer('NombreHeures');
            $table->timestamps();

            $table->foreign('formation_id')->references('id')->on('formations')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contenus_formations');
    }
};
