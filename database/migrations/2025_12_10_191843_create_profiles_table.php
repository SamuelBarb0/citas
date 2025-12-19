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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nombre');
            $table->integer('edad');
            $table->enum('genero', ['hombre', 'mujer', 'otro']);
            $table->enum('busco', ['hombre', 'mujer', 'ambos']);
            $table->text('biografia')->nullable();
            $table->string('ciudad')->default('Mallorca');
            $table->string('foto_principal')->nullable();
            $table->json('fotos_adicionales')->nullable();
            $table->json('intereses')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
