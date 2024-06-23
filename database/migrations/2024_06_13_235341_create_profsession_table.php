<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('prof_session')) {
            Schema::create('prof_session', function (Blueprint $table) {
                $table->bigInteger('prof_id')->unsigned();
                $table->bigInteger('session_id')->unsigned();
                $table->foreign('prof_id')->references('id')->on('professeurs');
                $table->foreign('session_id')->references('id')->on('sessions');
                $table->date('date')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('prof_session');
    }
};
