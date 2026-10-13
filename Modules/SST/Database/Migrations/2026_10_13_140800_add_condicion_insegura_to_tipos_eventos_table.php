<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE tipos_eventos MODIFY COLUMN tipo_categoria ENUM('accidente', 'incidente', 'riesgo', 'acto_inseguro', 'condicion_insegura', 'lesion', 'tipo_emergencia') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE tipos_eventos MODIFY COLUMN tipo_categoria ENUM('accidente', 'incidente', 'riesgo', 'acto_inseguro', 'lesion', 'tipo_emergencia') NOT NULL");
    }
};
