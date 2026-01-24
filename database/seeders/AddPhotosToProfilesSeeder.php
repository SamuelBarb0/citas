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
        $profiles = Profile::all();

        foreach ($profiles as $profile) {
            // Determinar si es hombre o mujer basado en el género
            $isMale = in_array(strtolower($profile->genero), ['hombre', 'male', 'masculino']);
            $gender = $isMale ? 'men' : 'women';

            // Generar 3-5 fotos adicionales
            $photoCount = rand(3, 5);
            $additionalPhotos = [];

            // Usar números únicos para evitar fotos repetidas
            $usedNumbers = [];

            for ($i = 0; $i < $photoCount; $i++) {
                // Generar número único (randomuser.me tiene fotos del 0 al 99)
                do {
                    $imgNum = rand(0, 99);
                } while (in_array($imgNum, $usedNumbers));
                $usedNumbers[] = $imgNum;

                // Usar randomuser.me que tiene fotos separadas por género
                $additionalPhotos[] = "https://randomuser.me/api/portraits/{$gender}/{$imgNum}.jpg";
            }

            // Actualizar el perfil con las fotos adicionales
            $profile->update([
                'fotos_adicionales' => $additionalPhotos
            ]);

            $this->command->info("✓ Agregadas {$photoCount} fotos de {$gender} a {$profile->nombre}");
        }

        $this->command->info("\n🎉 ¡Fotos agregadas exitosamente a todos los perfiles!");
    }
}
