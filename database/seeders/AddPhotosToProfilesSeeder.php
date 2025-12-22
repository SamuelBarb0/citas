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

            // Generar 3-5 fotos adicionales usando diferentes servicios
            $photoCount = rand(3, 5);
            $additionalPhotos = [];

            for ($i = 0; $i < $photoCount; $i++) {
                // Usar diferentes servicios de imágenes aleatorias
                $service = rand(1, 3);

                if ($service === 1) {
                    // Pravatar.cc
                    $imgNum = $isMale ? rand(10, 70) : rand(1, 70);
                    $additionalPhotos[] = "https://i.pravatar.cc/600?img={$imgNum}";
                } elseif ($service === 2) {
                    // UIFaces (más realistas)
                    $gender = $isMale ? 'male' : 'female';
                    $additionalPhotos[] = "https://randomuser.me/api/portraits/" . ($isMale ? 'men' : 'women') . "/" . rand(1, 99) . ".jpg";
                } else {
                    // This Person Does Not Exist (fotos muy realistas)
                    $additionalPhotos[] = "https://picsum.photos/seed/" . uniqid() . "/600/800";
                }
            }

            // Actualizar el perfil con las fotos adicionales
            $profile->update([
                'fotos_adicionales' => $additionalPhotos
            ]);

            $this->command->info("✓ Agregadas {$photoCount} fotos a {$profile->nombre}");
        }

        $this->command->info("\n🎉 ¡Fotos agregadas exitosamente a todos los perfiles!");
    }
}
