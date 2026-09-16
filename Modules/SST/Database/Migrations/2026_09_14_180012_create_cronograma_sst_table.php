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
        if (!Schema::hasTable('cronograma_sst')) {
            Schema::create('cronograma_sst', function (Blueprint $table) {
                $table->id('id_actividad');
                $table->unsignedBigInteger('id_fase')->nullable();
                $table->unsignedBigInteger('id_aprendiz')->nullable();
                $table->unsignedBigInteger('id_administrador')->nullable();
                $table->unsignedBigInteger('id_lugar')->nullable();
                $table->string('tipo_actividad', 100)->default('Capacitación');
                $table->string('nombre', 150);
                $table->date('fecha');
                $table->time('hora')->default('09:00:00');
                $table->string('responsable', 100);
                $table->enum('estado', ['Programada', 'En Ejecución', 'Completada', 'Cancelada'])->default('Programada');
                $table->text('descripcion')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cronograma_sst');
    }
};
