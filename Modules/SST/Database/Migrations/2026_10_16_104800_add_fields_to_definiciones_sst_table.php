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
        Schema::table('definiciones_sst', function (Blueprint $table) {
            if (!Schema::hasColumn('definiciones_sst', 'como_prevenir')) {
                $table->text('como_prevenir')->nullable()->after('descripcion');
            }
            if (!Schema::hasColumn('definiciones_sst', 'tipo_gravedad')) {
                $table->string('tipo_gravedad', 50)->nullable()->after('como_prevenir');
            }
            if (!Schema::hasColumn('definiciones_sst', 'tipo_clasificacion')) {
                $table->string('tipo_clasificacion', 100)->nullable()->after('tipo_gravedad');
            }
            if (!Schema::hasColumn('definiciones_sst', 'tipo_otro')) {
                $table->string('tipo_otro', 150)->nullable()->after('tipo_clasificacion');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('definiciones_sst', function (Blueprint $table) {
            $columnsToDrop = [];
            if (Schema::hasColumn('definiciones_sst', 'como_prevenir')) {
                $columnsToDrop[] = 'como_prevenir';
            }
            if (Schema::hasColumn('definiciones_sst', 'tipo_gravedad')) {
                $columnsToDrop[] = 'tipo_gravedad';
            }
            if (Schema::hasColumn('definiciones_sst', 'tipo_clasificacion')) {
                $columnsToDrop[] = 'tipo_clasificacion';
            }
            if (!empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }
};
