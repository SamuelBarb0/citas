<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;

class RemoveBotPhotosSeeder extends Seeder
{
    /**
     * Quita las fotos adicionales de los perfiles bot (@citasmallorca.es)
     * y les deja solo la foto principal.
     */
    public function run(): void
    {
        // Solo perfiles de usuarios con email @citasmallorca.es (bots de prueba)
        $profiles = Profile::whereHas('user', function ($query) {
            $query->where('email', 'like', '%@citasmallorca.es');
        })->get();

        $this->command->info("Encontrados {$profiles->count()} perfiles de prueba (@citasmallorca.es)");

        foreach ($profiles as $profile) {
            // Determinar si es hombre o mujer basado en el género
            $isMale = in_array(strtolower($profile->genero), ['hombre', 'male', 'masculino']);
            $gender = $isMale ? 'men' : 'women';

            // Generar solo foto principal con el género correcto
            $mainPhotoNum = rand(0, 99);
            $fotoPrincipal = "https://randomuser.me/api/portraits/{$gender}/{$mainPhotoNum}.jpg";

            // Actualizar el perfil: solo foto principal, quitar fotos adicionales
            $profile->update([
                'foto_principal' => $fotoPrincipal,
                'fotos_adicionales' => []
            ]);

            $this->command->info("✓ {$profile->nombre}: solo foto principal ({$gender})");
        }

        $this->command->info("\n🎉 ¡Fotos adicionales eliminadas de los bots!");
    }
}
