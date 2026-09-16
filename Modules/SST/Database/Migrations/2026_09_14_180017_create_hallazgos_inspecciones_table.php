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
        if (!Schema::hasTable('hallazgos_inspecciones')) {
            Schema::create('hallazgos_inspecciones', function (Blueprint $table) {
                $table->id('id_hallazgo');
                $table->unsignedBigInteger('id_inspeccion');
                $table->text('descripcion_hallazgo');
                $table->enum('nivel_riesgo', ['bajo', 'medio', 'alto', 'critico'])->default('medio');
                $table->text('accion_correctiva')->nullable();
                $table->string('responsable', 100)->nullable();
                $table->date('fecha_compromiso')->nullable();
                $table->enum('estado', ['abierto', 'en_proceso', 'cerrado'])->default('abierto');
                $table->timestamps();

                $table->foreign('id_inspeccion')->references('id_inspeccion')->on('inspecciones_sst')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hallazgos_inspecciones');
    }
};
