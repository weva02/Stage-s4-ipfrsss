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
        Schema::create('prof_session', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('prof_id')->unsigned();
            $table->bigInteger('session_id')->unsigned();
            $table->foreign('prof_id')->references('id')->on('professeurs');
            $table->foreign('session_id')->references('id')->on('sessions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prof_session');
    }
};
