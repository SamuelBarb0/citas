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
        Schema::table('profiles', function (Blueprint $table) {
            // Cambiar las columnas de enum a string para permitir más opciones
            DB::statement("ALTER TABLE profiles MODIFY genero VARCHAR(50)");
            DB::statement("ALTER TABLE profiles MODIFY busco VARCHAR(50)");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            // Volver a los valores enum originales
            DB::statement("ALTER TABLE profiles MODIFY genero ENUM('hombre', 'mujer', 'otro')");
            DB::statement("ALTER TABLE profiles MODIFY busco ENUM('hombre', 'mujer', 'ambos')");
        });
    }
};
