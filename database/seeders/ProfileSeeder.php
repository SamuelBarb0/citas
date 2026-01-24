<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Support\Facades\Hash;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fotos de mujeres (randomuser.me/api/portraits/women/X.jpg)
        // Fotos de hombres (randomuser.me/api/portraits/men/X.jpg)
        $perfiles = [
            [
                'nombre' => 'María',
                'edad' => 28,
                'genero' => 'mujer',
                'busco' => 'hombre',
                'biografia' => 'Amante del mar y las puestas de sol en Palma. Me encanta el senderismo por la Serra de Tramuntana y disfrutar de una buena sobrasada.',
                'ciudad' => 'Palma de Mallorca',
                'intereses' => ['playa', 'senderismo', 'cocina', 'yoga'],
                'foto_principal' => 'https://randomuser.me/api/portraits/women/44.jpg',
            ],
            [
                'nombre' => 'Carlos',
                'edad' => 32,
                'genero' => 'hombre',
                'busco' => 'mujer',
                'biografia' => 'Profesor de windsurf en Alcúdia. Si te gusta la aventura y el deporte acuático, conectemos!',
                'ciudad' => 'Alcúdia',
                'intereses' => ['deportes acuáticos', 'playa', 'música en vivo', 'viajes'],
                'foto_principal' => 'https://randomuser.me/api/portraits/men/32.jpg',
            ],
            [
                'nombre' => 'Laura',
                'edad' => 26,
                'genero' => 'mujer',
                'busco' => 'ambos',
                'biografia' => 'Fotógrafa freelance viviendo el sueño mediterráneo. Café por la mañana, playa por la tarde.',
                'ciudad' => 'Sóller',
                'intereses' => ['fotografía', 'arte', 'café', 'mercadillos'],
                'foto_principal' => 'https://randomuser.me/api/portraits/women/68.jpg',
            ],
            [
                'nombre' => 'Miguel',
                'edad' => 35,
                'genero' => 'hombre',
                'busco' => 'mujer',
                'biografia' => 'Chef en un restaurante local. La comida es mi pasión, especialmente la mediterránea con un toque moderno.',
                'ciudad' => 'Palma de Mallorca',
                'intereses' => ['gastronomía', 'vino', 'cocina', 'ciclismo'],
                'foto_principal' => 'https://randomuser.me/api/portraits/men/75.jpg',
            ],
            [
                'nombre' => 'Ana',
                'edad' => 30,
                'genero' => 'mujer',
                'busco' => 'hombre',
                'biografia' => 'Arquitecta enamorada de los pueblos con encanto de Mallorca. Busco alguien para explorar calas escondidas.',
                'ciudad' => 'Valldemossa',
                'intereses' => ['arquitectura', 'calas', 'lectura', 'paddle surf'],
                'foto_principal' => 'https://randomuser.me/api/portraits/women/33.jpg',
            ],
            [
                'nombre' => 'Javier',
                'edad' => 29,
                'genero' => 'hombre',
                'busco' => 'mujer',
                'biografia' => 'Desarrollador de software trabajando en remoto. Me gusta equilibrar la vida digital con experiencias reales.',
                'ciudad' => 'Palma de Mallorca',
                'intereses' => ['tecnología', 'running', 'cine', 'tapas'],
                'foto_principal' => 'https://randomuser.me/api/portraits/men/41.jpg',
            ],
            [
                'nombre' => 'Sofia',
                'edad' => 27,
                'genero' => 'mujer',
                'busco' => 'hombre',
                'biografia' => 'Bailarina profesional. La música y el baile son mi vida. Busco a alguien que quiera bailar bajo las estrellas.',
                'ciudad' => 'Manacor',
                'intereses' => ['baile', 'música', 'festivales', 'naturaleza'],
                'foto_principal' => 'https://randomuser.me/api/portraits/women/89.jpg',
            ],
            [
                'nombre' => 'David',
                'edad' => 31,
                'genero' => 'hombre',
                'busco' => 'mujer',
                'biografia' => 'Guía turístico mostrando lo mejor de Mallorca. Conozco todos los rincones secretos de la isla.',
                'ciudad' => 'Inca',
                'intereses' => ['historia', 'excursiones', 'idiomas', 'fotografía'],
                'foto_principal' => 'https://randomuser.me/api/portraits/men/22.jpg',
            ],
        ];

        // Mapeo de nombres a emails sin acentos
        $emailMap = [
            'María' => 'maria',
            'Carlos' => 'carlos',
            'Laura' => 'laura',
            'Miguel' => 'miguel',
            'Ana' => 'ana',
            'Javier' => 'javier',
            'Sofía' => 'sofia',
            'David' => 'david',
        ];

        foreach ($perfiles as $perfilData) {
            $emailPrefix = $emailMap[$perfilData['nombre']] ?? strtolower($perfilData['nombre']);

            $user = User::create([
                'name' => $perfilData['nombre'],
                'email' => $emailPrefix . '@citasmallorca.es',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);

            Profile::create([
                'user_id' => $user->id,
                'nombre' => $perfilData['nombre'],
                'edad' => $perfilData['edad'],
                'genero' => $perfilData['genero'],
                'busco' => $perfilData['busco'],
                'biografia' => $perfilData['biografia'],
                'ciudad' => $perfilData['ciudad'],
                'foto_principal' => $perfilData['foto_principal'],
                'intereses' => $perfilData['intereses'],
                'activo' => true,
            ]);
        }
    }
}
