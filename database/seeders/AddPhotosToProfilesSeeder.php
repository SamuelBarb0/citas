<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Profile;

class AddPhotosToProfilesSeeder extends Seeder
{
    /**
     * Run the database seeds.
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

            // Usar números únicos para evitar fotos repetidas
            $usedNumbers = [];

            // Generar foto principal con el género correcto
            $mainPhotoNum = rand(0, 99);
            $usedNumbers[] = $mainPhotoNum;
            $fotoPrincipal = "https://randomuser.me/api/portraits/{$gender}/{$mainPhotoNum}.jpg";

            // Generar 3-5 fotos adicionales
            $photoCount = rand(3, 5);
            $additionalPhotos = [];

            for ($i = 0; $i < $photoCount; $i++) {
                // Generar número único (randomuser.me tiene fotos del 0 al 99)
                do {
                    $imgNum = rand(0, 99);
                } while (in_array($imgNum, $usedNumbers));
                $usedNumbers[] = $imgNum;

                // Usar randomuser.me que tiene fotos separadas por género
                $additionalPhotos[] = "https://randomuser.me/api/portraits/{$gender}/{$imgNum}.jpg";
            }

            // Actualizar el perfil con foto principal y adicionales
            $profile->update([
                'foto_principal' => $fotoPrincipal,
                'fotos_adicionales' => $additionalPhotos
            ]);

            $this->command->info("✓ Actualizadas fotos de {$gender} para {$profile->nombre}");
        }

        $this->command->info("\n🎉 ¡Fotos actualizadas exitosamente en todos los perfiles!");
    }
}
