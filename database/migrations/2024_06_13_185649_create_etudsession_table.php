<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etud_session', function (Blueprint $table) {
            $table->bigInteger('etudiant_id')->unsigned();
            $table->bigInteger('session_id')->unsigned();
            $table->foreign('etudiant_id')->references('id')->on('etudiants');
            $table->foreign('session_id')->references('id')->on('sessions');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etud_session');
    }
};
