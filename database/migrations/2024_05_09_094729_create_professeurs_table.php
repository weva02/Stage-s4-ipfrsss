<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('professeurs')) {
            Schema::create('professeurs', function (Blueprint $table) {
                $table->id();
                $table->string('image');
                $table->string('nomprenom');
                $table->string('nationalite');
                $table->string('email');
                $table->string('diplome');
                $table->integer('phone');
                $table->integer('wtsp');
                $table->unsignedBigInteger('typeymntprof_id');
                $table->foreign('typeymntprof_id')->references('id')->on('typeymntprofs')->onDelete('cascade');
                $table->unsignedBigInteger('country_id');
                $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('professeurs');
    }
};
