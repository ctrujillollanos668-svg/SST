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
        if (!Schema::hasTable('indicadores_sst')) {
            Schema::create('indicadores_sst', function (Blueprint $table) {
                $table->id('id_indicador');
                $table->string('nombre', 150);
                $table->enum('tipo', ['estructura', 'proceso', 'resultado'])->default('proceso');
                $table->text('formula')->nullable();
                $table->string('meta', 50)->nullable();
                $table->string('periodicidad', 50)->default('Mensual');
                $table->string('resultado_actual', 50)->nullable();
                $table->string('cumplimiento', 50)->default('Cumplido');
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
        Schema::dropIfExists('indicadores_sst');
    }
};
