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
        if (!Schema::hasTable('tipos_eventos')) {
            Schema::create('tipos_eventos', function (Blueprint $table) {
                $table->id('id_tipo_evento');
                $table->enum('tipo_categoria', ['accidente', 'incidente', 'riesgo', 'acto_inseguro', 'lesion', 'tipo_emergencia']);
                $table->unsignedBigInteger('id_aprendiz')->nullable();
                $table->unsignedBigInteger('id_fase')->nullable();
                $table->string('nombre', 100);
                $table->text('descripcion')->nullable();
                $table->string('modulo_pertenece', 150)->nullable();
                $table->boolean('notificaciones')->default(false);
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
        Schema::dropIfExists('tipos_eventos');
    }
};
