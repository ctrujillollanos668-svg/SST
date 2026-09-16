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
        if (!Schema::hasTable('inspecciones_sst')) {
            Schema::create('inspecciones_sst', function (Blueprint $table) {
                $table->id('id_inspeccion');
                $table->unsignedBigInteger('lugar_id')->nullable();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('tipo_inspeccion', 100);
                $table->date('fecha_inspeccion');
                $table->time('hora_inspeccion')->nullable();
                $table->enum('estado', ['pendiente', 'en_proceso', 'completada', 'con_hallazgos'])->default('completada');
                $table->text('observaciones')->nullable();
                $table->timestamps();

                $table->foreign('lugar_id')->references('id_lugar')->on('lugares_formacion')->onDelete('set null');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspecciones_sst');
    }
};
