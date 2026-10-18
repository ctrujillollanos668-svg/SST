<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDefinicionesSstTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('definiciones_sst')) {
            Schema::create('definiciones_sst', function (Blueprint $table) {
                $table->id('id_definicion');
                $table->string('tipo_categoria', 50)->comment('accidente, incidente, riesgo, acto_inseguro, lesion, tipo_emergencia');
                $table->string('nombre', 150);
                $table->text('descripcion');
                $table->text('como_prevenir')->nullable();
                $table->string('tipo_gravedad', 50)->nullable();
                $table->string('tipo_clasificacion', 100)->nullable();
                $table->string('tipo_otro', 150)->nullable();
                $table->string('modulo_pertenece', 150)->nullable();
                $table->enum('estado', ['activo', 'inactivo'])->default('activo');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('definiciones_sst');
    }
}
