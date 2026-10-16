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
        if (!Schema::hasTable('actividades_sst')) {
            Schema::create('actividades_sst', function (Blueprint $table) {
                $table->id('id_actividad');
                $table->enum('tipo_actividad', ['Pausa Activa', 'Charla', 'Sensibilización'])->default('Pausa Activa');
                $table->string('titulo', 150);
                $table->text('descripcion')->nullable();
                $table->text('actividad_a_realizar')->nullable();
                $table->date('fecha');
                $table->time('hora_inicio');
                $table->time('hora_fin')->nullable();
                $table->string('lugar', 150)->nullable();
                $table->string('instructor', 150)->nullable();
                $table->string('responsable', 150)->nullable();
                $table->string('ficha_programa', 150)->nullable();
                $table->string('participantes', 255)->nullable();
                $table->integer('numero_asistentes')->default(0);
                $table->text('observaciones')->nullable();
                $table->enum('estado', ['Programada', 'Realizada', 'Cancelada'])->default('Programada');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actividades_sst');
    }
};
