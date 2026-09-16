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
        if (!Schema::hasTable('pausas_activas')) {
            Schema::create('pausas_activas', function (Blueprint $table) {
                $table->id('id_pausa');
                $table->unsignedBigInteger('id_fase')->nullable();
                $table->string('titulo', 100);
                $table->text('descripcion')->nullable();
                $table->date('fecha')->nullable();
                $table->time('hora_inicio')->nullable();
                $table->time('hora_fin')->nullable();
                $table->string('lugar', 150)->nullable();
                $table->enum('estado', ['activo', 'inactivo'])->default('activo');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pausas_activas');
    }
};
