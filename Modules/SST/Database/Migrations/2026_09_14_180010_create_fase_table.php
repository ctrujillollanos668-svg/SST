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
        if (!Schema::hasTable('fase')) {
            Schema::create('fase', function (Blueprint $table) {
                $table->id('id_fase');
                $table->string('nombre', 100);
                $table->text('descripcion')->nullable();
                $table->date('fecha_inicio')->nullable();
                $table->date('fecha_fin')->nullable();
                $table->enum('estado', ['configurada', 'activo', 'finalizado'])->default('configurada');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fase');
    }
};
