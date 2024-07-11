<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('prof_session')) {
            Schema::create('prof_session', function (Blueprint $table) {
                $table->foreignId('prof_id')->constrained('Professeurs');
                $table->foreignId('session_id')->constrained('sessions');
                // $table->foreignId('typeymntprofs_id')->constrained('typeymntprofs');
                $table->date('date_paiement')->nullable();
                $table->timestamps();

                $table->primary(['prof_id', 'session_id']);
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('prof_session');
    }
};
