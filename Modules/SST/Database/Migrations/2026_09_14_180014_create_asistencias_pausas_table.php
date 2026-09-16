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
        if (!Schema::hasTable('asistencias_pausas')) {
            Schema::create('asistencias_pausas', function (Blueprint $table) {
                $table->id('id_asistencia');
                $table->unsignedBigInteger('id_pausa');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->unsignedBigInteger('id_persona')->nullable();
                $table->dateTime('fecha_registro')->nullable();
                $table->string('persona_responsable', 100)->nullable();
                $table->enum('estado', ['registrado', 'no_registrado', 'justificado'])->default('registrado');
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
        Schema::dropIfExists('asistencias_pausas');
    }
};
