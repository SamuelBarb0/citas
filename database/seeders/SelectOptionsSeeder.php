<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SelectOption;

class SelectOptionsSeeder extends Seeder
{
    public function run(): void
    {
        // Ciudades de Mallorca
        $ciudades = [
            // Principales ciudades (orden 1-5)
            ['valor' => 'Palma de Mallorca', 'etiqueta' => 'Palma de Mallorca', 'grupo' => 'Principales Ciudades', 'orden' => 1],
            ['valor' => 'Manacor', 'etiqueta' => 'Manacor', 'grupo' => 'Principales Ciudades', 'orden' => 2],
            ['valor' => 'Inca', 'etiqueta' => 'Inca', 'grupo' => 'Principales Ciudades', 'orden' => 3],
            ['valor' => 'Calvià', 'etiqueta' => 'Calvià', 'grupo' => 'Principales Ciudades', 'orden' => 4],
            ['valor' => 'Llucmajor', 'etiqueta' => 'Llucmajor', 'grupo' => 'Principales Ciudades', 'orden' => 5],

            // Todos los municipios de Mallorca (orden 10+)
            ['valor' => 'Alaró', 'etiqueta' => 'Alaró', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 10],
            ['valor' => 'Alcúdia', 'etiqueta' => 'Alcúdia', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 11],
            ['valor' => 'Algaida', 'etiqueta' => 'Algaida', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 12],
            ['valor' => 'Andratx', 'etiqueta' => 'Andratx', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 13],
            ['valor' => 'Ariany', 'etiqueta' => 'Ariany', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 14],
            ['valor' => 'Artà', 'etiqueta' => 'Artà', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 15],
            ['valor' => 'Banyalbufar', 'etiqueta' => 'Banyalbufar', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 16],
            ['valor' => 'Binissalem', 'etiqueta' => 'Binissalem', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 17],
            ['valor' => 'Búger', 'etiqueta' => 'Búger', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 18],
            ['valor' => 'Bunyola', 'etiqueta' => 'Bunyola', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 19],
            ['valor' => 'Campanet', 'etiqueta' => 'Campanet', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 20],
            ['valor' => 'Campos', 'etiqueta' => 'Campos', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 21],
            ['valor' => 'Capdepera', 'etiqueta' => 'Capdepera', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 22],
            ['valor' => 'Consell', 'etiqueta' => 'Consell', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 23],
            ['valor' => 'Costitx', 'etiqueta' => 'Costitx', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 24],
            ['valor' => 'Deià', 'etiqueta' => 'Deià', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 25],
            ['valor' => 'Escorca', 'etiqueta' => 'Escorca', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 26],
            ['valor' => 'Esporles', 'etiqueta' => 'Esporles', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 27],
            ['valor' => 'Estellencs', 'etiqueta' => 'Estellencs', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 28],
            ['valor' => 'Felanitx', 'etiqueta' => 'Felanitx', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 29],
            ['valor' => 'Fornalutx', 'etiqueta' => 'Fornalutx', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 30],
            ['valor' => 'Lloret de Vistalegre', 'etiqueta' => 'Lloret de Vistalegre', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 31],
            ['valor' => 'Lloseta', 'etiqueta' => 'Lloseta', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 32],
            ['valor' => 'Magaluf', 'etiqueta' => 'Magaluf', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 33],
            ['valor' => 'Mancor de la Vall', 'etiqueta' => 'Mancor de la Vall', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 34],
            ['valor' => 'Maria de la Salut', 'etiqueta' => 'Maria de la Salut', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 35],
            ['valor' => 'Marratxí', 'etiqueta' => 'Marratxí', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 36],
            ['valor' => 'Montuïri', 'etiqueta' => 'Montuïri', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 37],
            ['valor' => 'Muro', 'etiqueta' => 'Muro', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 38],
            ['valor' => 'Petra', 'etiqueta' => 'Petra', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 39],
            ['valor' => 'Pollença', 'etiqueta' => 'Pollença', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 40],
            ['valor' => 'Porreres', 'etiqueta' => 'Porreres', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 41],
            ['valor' => 'Puigpunyent', 'etiqueta' => 'Puigpunyent', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 42],
            ['valor' => 'Sa Pobla', 'etiqueta' => 'Sa Pobla', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 43],
            ['valor' => 'Sant Joan', 'etiqueta' => 'Sant Joan', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 44],
            ['valor' => 'Sant Llorenç des Cardassar', 'etiqueta' => 'Sant Llorenç des Cardassar', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 45],
            ['valor' => 'Santa Eugènia', 'etiqueta' => 'Santa Eugènia', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 46],
            ['valor' => 'Santa Margalida', 'etiqueta' => 'Santa Margalida', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 47],
            ['valor' => 'Santa Maria del Camí', 'etiqueta' => 'Santa Maria del Camí', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 48],
            ['valor' => 'Santanyí', 'etiqueta' => 'Santanyí', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 49],
            ['valor' => 'Selva', 'etiqueta' => 'Selva', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 50],
            ['valor' => 'Sencelles', 'etiqueta' => 'Sencelles', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 51],
            ['valor' => 'Ses Salines', 'etiqueta' => 'Ses Salines', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 52],
            ['valor' => 'Sineu', 'etiqueta' => 'Sineu', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 53],
            ['valor' => 'Sóller', 'etiqueta' => 'Sóller', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 54],
            ['valor' => 'Son Servera', 'etiqueta' => 'Son Servera', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 55],
            ['valor' => 'Valldemossa', 'etiqueta' => 'Valldemossa', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 56],
            ['valor' => 'Vilafranca de Bonany', 'etiqueta' => 'Vilafranca de Bonany', 'grupo' => 'Todos los Municipios de Mallorca', 'orden' => 57],

            // Otras opciones (orden 100+)
            ['valor' => 'Otro pueblo de Mallorca', 'etiqueta' => 'Otro pueblo de Mallorca', 'grupo' => 'Otras Opciones', 'orden' => 100],
            ['valor' => 'Otra isla (Menorca, Ibiza, Formentera)', 'etiqueta' => 'Otra isla (Menorca, Ibiza, Formentera)', 'grupo' => 'Otras Opciones', 'orden' => 101],
            ['valor' => 'Península (España)', 'etiqueta' => 'Península (España)', 'grupo' => 'Otras Opciones', 'orden' => 102],
            ['valor' => 'Fuera de España', 'etiqueta' => 'Fuera de España', 'grupo' => 'Otras Opciones', 'orden' => 103],
        ];

        foreach ($ciudades as $ciudad) {
            SelectOption::create([
                'tipo' => 'ciudad',
                'valor' => $ciudad['valor'],
                'etiqueta' => $ciudad['etiqueta'],
                'grupo' => $ciudad['grupo'],
                'orden' => $ciudad['orden'],
                'activo' => true,
            ]);
        }

        // Géneros
        $generos = [
            ['valor' => 'hombre', 'etiqueta' => 'Hombre', 'orden' => 1],
            ['valor' => 'mujer', 'etiqueta' => 'Mujer', 'orden' => 2],
            ['valor' => 'hombre_trans', 'etiqueta' => 'Hombre Trans', 'orden' => 3],
            ['valor' => 'mujer_trans', 'etiqueta' => 'Mujer Trans', 'orden' => 4],
            ['valor' => 'no_binario', 'etiqueta' => 'No Binario', 'orden' => 5],
            ['valor' => 'otro', 'etiqueta' => 'Otro', 'orden' => 6],
        ];

        foreach ($generos as $genero) {
            SelectOption::create([
                'tipo' => 'genero',
                'valor' => $genero['valor'],
                'etiqueta' => $genero['etiqueta'],
                'grupo' => null,
                'orden' => $genero['orden'],
                'activo' => true,
            ]);
        }

        // Qué busco
        $busco = [
            ['valor' => 'hombre', 'etiqueta' => 'Hombres', 'orden' => 1],
            ['valor' => 'mujer', 'etiqueta' => 'Mujeres', 'orden' => 2],
            ['valor' => 'hombre_trans', 'etiqueta' => 'Hombres Trans', 'orden' => 3],
            ['valor' => 'mujer_trans', 'etiqueta' => 'Mujeres Trans', 'orden' => 4],
            ['valor' => 'no_binario', 'etiqueta' => 'Personas No Binarias', 'orden' => 5],
            ['valor' => 'cualquiera', 'etiqueta' => 'Cualquiera', 'orden' => 6],
        ];

        foreach ($busco as $opcion) {
            SelectOption::create([
                'tipo' => 'busco',
                'valor' => $opcion['valor'],
                'etiqueta' => $opcion['etiqueta'],
                'grupo' => null,
                'orden' => $opcion['orden'],
                'activo' => true,
            ]);
        }

        // Orientaciones sexuales
        $orientaciones = [
            ['valor' => 'heterosexual', 'etiqueta' => 'Heterosexual', 'orden' => 1],
            ['valor' => 'homosexual', 'etiqueta' => 'Homosexual', 'orden' => 2],
            ['valor' => 'bisexual', 'etiqueta' => 'Bisexual', 'orden' => 3],
            ['valor' => 'pansexual', 'etiqueta' => 'Pansexual', 'orden' => 4],
            ['valor' => 'asexual', 'etiqueta' => 'Asexual', 'orden' => 5],
            ['valor' => 'queer', 'etiqueta' => 'Queer', 'orden' => 6],
            ['valor' => 'otro', 'etiqueta' => 'Otro', 'orden' => 7],
            ['valor' => 'prefiero_no_decir', 'etiqueta' => 'Prefiero no decir', 'orden' => 8],
        ];

        foreach ($orientaciones as $orientacion) {
            SelectOption::create([
                'tipo' => 'orientacion_sexual',
                'valor' => $orientacion['valor'],
                'etiqueta' => $orientacion['etiqueta'],
                'grupo' => null,
                'orden' => $orientacion['orden'],
                'activo' => true,
            ]);
        }

        $this->command->info('✓ Opciones de select creadas exitosamente');
    }
}
