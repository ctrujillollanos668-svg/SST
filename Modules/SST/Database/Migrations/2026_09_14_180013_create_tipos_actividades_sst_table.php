<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('tipos_actividades_sst')) {
            Schema::create('tipos_actividades_sst', function (Blueprint $table) {
                $table->id('id_tipo_actividad');
                $table->string('nombre', 100)->unique();
                $table->string('icono', 50)->default('fa-calendar-check');
                $table->string('color', 50)->default('blue');
                $table->enum('estado', ['activo', 'inactivo'])->default('activo');
                $table->timestamps();
            });

            // Seed inicial con tipos estándar
            $defaults = [
                ['nombre' => 'Capacitación', 'icono' => 'fa-graduation-cap', 'color' => 'blue'],
                ['nombre' => 'Simulacro', 'icono' => 'fa-triangle-exclamation', 'color' => 'rose'],
                ['nombre' => 'Inspección', 'icono' => 'fa-clipboard-check', 'color' => 'purple'],
                ['nombre' => 'Pausa Activa', 'icono' => 'fa-person-running', 'color' => 'teal'],
                ['nombre' => 'Charla 5 Min', 'icono' => 'fa-bullhorn', 'color' => 'amber'],
                ['nombre' => 'Reunión COPASST', 'icono' => 'fa-users', 'color' => 'indigo'],
                ['nombre' => 'Jornada de Salud', 'icono' => 'fa-heart-pulse', 'color' => 'emerald'],
                ['nombre' => 'Otro', 'icono' => 'fa-bookmark', 'color' => 'slate'],
            ];

            foreach ($defaults as $item) {
                DB::table('tipos_actividades_sst')->insert([
                    'nombre' => $item['nombre'],
                    'icono' => $item['icono'],
                    'color' => $item['color'],
                    'estado' => 'activo',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipos_actividades_sst');
    }
};
