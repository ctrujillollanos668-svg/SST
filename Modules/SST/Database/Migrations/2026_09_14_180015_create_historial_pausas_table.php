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
        if (!Schema::hasTable('historial_pausas')) {
            Schema::create('historial_pausas', function (Blueprint $table) {
                $table->id('id_historial');
                $table->unsignedBigInteger('id_pausa')->nullable();
                $table->unsignedBigInteger('id_asistencia')->nullable();
                $table->text('observacion')->nullable();
                $table->dateTime('fecha')->nullable();
                $table->timestamps();

                $table->foreign('id_pausa')->references('id_pausa')->on('pausas_activas')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_pausas');
    }
};
