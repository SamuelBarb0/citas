<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SelectOption;

class SelectOptionsSeeder extends Seeder
{
    public function run(): void
    {
        // Eliminar opciones de ciudad existentes para evitar duplicados
        SelectOption::where('tipo', 'ciudad')->delete();

        // Todos los municipios de Mallorca (alfabeticamente)
        $ciudades = [
            ['valor' => 'Alaró', 'etiqueta' => 'Alaró', 'orden' => 1],
            ['valor' => 'Alcúdia', 'etiqueta' => 'Alcúdia', 'orden' => 2],
            ['valor' => 'Algaida', 'etiqueta' => 'Algaida', 'orden' => 3],
            ['valor' => 'Andratx', 'etiqueta' => 'Andratx', 'orden' => 4],
            ['valor' => 'Ariany', 'etiqueta' => 'Ariany', 'orden' => 5],
            ['valor' => 'Artà', 'etiqueta' => 'Artà', 'orden' => 6],
            ['valor' => 'Banyalbufar', 'etiqueta' => 'Banyalbufar', 'orden' => 7],
            ['valor' => 'Binissalem', 'etiqueta' => 'Binissalem', 'orden' => 8],
            ['valor' => 'Búger', 'etiqueta' => 'Búger', 'orden' => 9],
            ['valor' => 'Bunyola', 'etiqueta' => 'Bunyola', 'orden' => 10],
            ['valor' => 'Calvià', 'etiqueta' => 'Calvià', 'orden' => 11],
            ['valor' => 'Campanet', 'etiqueta' => 'Campanet', 'orden' => 12],
            ['valor' => 'Campos', 'etiqueta' => 'Campos', 'orden' => 13],
            ['valor' => 'Capdepera', 'etiqueta' => 'Capdepera', 'orden' => 14],
            ['valor' => 'Consell', 'etiqueta' => 'Consell', 'orden' => 15],
            ['valor' => 'Costitx', 'etiqueta' => 'Costitx', 'orden' => 16],
            ['valor' => 'Deià', 'etiqueta' => 'Deià', 'orden' => 17],
            ['valor' => 'Escorca', 'etiqueta' => 'Escorca', 'orden' => 18],
            ['valor' => 'Esporles', 'etiqueta' => 'Esporles', 'orden' => 19],
            ['valor' => 'Estellencs', 'etiqueta' => 'Estellencs', 'orden' => 20],
            ['valor' => 'Felanitx', 'etiqueta' => 'Felanitx', 'orden' => 21],
            ['valor' => 'Fornalutx', 'etiqueta' => 'Fornalutx', 'orden' => 22],
            ['valor' => 'Inca', 'etiqueta' => 'Inca', 'orden' => 23],
            ['valor' => 'Lloret de Vistalegre', 'etiqueta' => 'Lloret de Vistalegre', 'orden' => 24],
            ['valor' => 'Lloseta', 'etiqueta' => 'Lloseta', 'orden' => 25],
            ['valor' => 'Llubí', 'etiqueta' => 'Llubí', 'orden' => 26],
            ['valor' => 'Llucmajor', 'etiqueta' => 'Llucmajor', 'orden' => 27],
            ['valor' => 'Manacor', 'etiqueta' => 'Manacor', 'orden' => 28],
            ['valor' => 'Mancor de la Vall', 'etiqueta' => 'Mancor de la Vall', 'orden' => 29],
            ['valor' => 'Maria de la Salut', 'etiqueta' => 'Maria de la Salut', 'orden' => 30],
            ['valor' => 'Marratxí', 'etiqueta' => 'Marratxí', 'orden' => 31],
            ['valor' => 'Montuïri', 'etiqueta' => 'Montuïri', 'orden' => 32],
            ['valor' => 'Muro', 'etiqueta' => 'Muro', 'orden' => 33],
            ['valor' => 'Palma', 'etiqueta' => 'Palma', 'orden' => 34],
            ['valor' => 'Petra', 'etiqueta' => 'Petra', 'orden' => 35],
            ['valor' => 'Pollença', 'etiqueta' => 'Pollença', 'orden' => 36],
            ['valor' => 'Porreres', 'etiqueta' => 'Porreres', 'orden' => 37],
            ['valor' => 'Puigpunyent', 'etiqueta' => 'Puigpunyent', 'orden' => 38],
            ['valor' => 'Sa Pobla', 'etiqueta' => 'Sa Pobla', 'orden' => 39],
            ['valor' => 'Sant Joan', 'etiqueta' => 'Sant Joan', 'orden' => 40],
            ['valor' => 'Sant Llorenç des Cardassar', 'etiqueta' => 'Sant Llorenç des Cardassar', 'orden' => 41],
            ['valor' => 'Santa Eugènia', 'etiqueta' => 'Santa Eugènia', 'orden' => 42],
            ['valor' => 'Santa Margalida', 'etiqueta' => 'Santa Margalida', 'orden' => 43],
            ['valor' => 'Santa Maria del Camí', 'etiqueta' => 'Santa Maria del Camí', 'orden' => 44],
            ['valor' => 'Santanyí', 'etiqueta' => 'Santanyí', 'orden' => 45],
            ['valor' => 'Selva', 'etiqueta' => 'Selva', 'orden' => 46],
            ['valor' => 'Sencelles', 'etiqueta' => 'Sencelles', 'orden' => 47],
            ['valor' => 'Ses Salines', 'etiqueta' => 'Ses Salines', 'orden' => 48],
            ['valor' => 'Sineu', 'etiqueta' => 'Sineu', 'orden' => 49],
            ['valor' => 'Sóller', 'etiqueta' => 'Sóller', 'orden' => 50],
            ['valor' => 'Son Servera', 'etiqueta' => 'Son Servera', 'orden' => 51],
            ['valor' => 'Valldemossa', 'etiqueta' => 'Valldemossa', 'orden' => 52],
            ['valor' => 'Vilafranca de Bonany', 'etiqueta' => 'Vilafranca de Bonany', 'orden' => 53],
        ];

        foreach ($ciudades as $ciudad) {
            SelectOption::create([
                'tipo' => 'ciudad',
                'valor' => $ciudad['valor'],
                'etiqueta' => $ciudad['etiqueta'],
                'grupo' => null,
                'orden' => $ciudad['orden'],
                'activo' => true,
            ]);
        }

        // Otras opciones
        $otrasOpciones = [
            ['valor' => 'Otro pueblo de Mallorca', 'etiqueta' => 'Otro pueblo de Mallorca', 'orden' => 100],
            ['valor' => 'Otra isla', 'etiqueta' => 'Otra isla', 'orden' => 101],
            ['valor' => 'Península (España)', 'etiqueta' => 'Península (España)', 'orden' => 102],
            ['valor' => 'Fuera de España', 'etiqueta' => 'Fuera de España', 'orden' => 103],
        ];

        foreach ($otrasOpciones as $opcion) {
            SelectOption::create([
                'tipo' => 'ciudad',
                'valor' => $opcion['valor'],
                'etiqueta' => $opcion['etiqueta'],
                'grupo' => 'Otras opciones',
                'orden' => $opcion['orden'],
                'activo' => true,
            ]);
        }

        // Eliminar opciones de genero existentes para evitar duplicados
        SelectOption::where('tipo', 'genero')->delete();

        // Géneros
        $generos = [
            ['valor' => 'hombre', 'etiqueta' => 'Hombre', 'orden' => 1],
            ['valor' => 'mujer', 'etiqueta' => 'Mujer', 'orden' => 2],
            ['valor' => 'persona_no_binaria', 'etiqueta' => 'Persona no binaria', 'orden' => 3],
            ['valor' => 'genero_fluido', 'etiqueta' => 'Género fluido', 'orden' => 4],
            ['valor' => 'prefiero_no_decirlo', 'etiqueta' => 'Prefiero no decirlo', 'orden' => 5],
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

        // Eliminar opciones de busco existentes para evitar duplicados
        SelectOption::where('tipo', 'busco')->delete();

        // Qué busco
        $busco = [
            ['valor' => 'hombre', 'etiqueta' => 'Hombres', 'orden' => 1],
            ['valor' => 'mujer', 'etiqueta' => 'Mujeres', 'orden' => 2],
            ['valor' => 'persona_no_binaria', 'etiqueta' => 'Personas no binarias', 'orden' => 3],
            ['valor' => 'genero_fluido', 'etiqueta' => 'Personas de género fluido', 'orden' => 4],
            ['valor' => 'cualquiera', 'etiqueta' => 'Cualquiera', 'orden' => 5],
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

        $this->command->info('Opciones de select creadas exitosamente');
    }
}
