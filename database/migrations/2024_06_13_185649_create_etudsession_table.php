<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('etud_session', function (Blueprint $table) {
            $table->foreignId('etudiant_id')->constrained('etudiants')->onDelete('cascade');
            $table->foreignId('session_id')->constrained('sessions')->onDelete('cascade');
            $table->date('date_paiement')->nullable();
            $table->decimal('montant_paye', 8, 2)->nullable();
            $table->foreignId('mode_paiement_id')->constrained('modes_paiement')->onDelete('cascade');
            $table->decimal('reste_a_payer', 8, 2)->nullable();
            $table->timestamps();

            $table->primary(['etudiant_id', 'session_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('etud_session');
    }
};
