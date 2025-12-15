<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Career;

class CareersSeeder extends Seeder
{
    public function run(): void
{
    Career::firstOrCreate(
        ['codigo' => 'TUP'],
        ['nombre' => 'Tecnicatura Universitaria en Programación']
    );

    Career::firstOrCreate(
        ['codigo' => 'LPB'],
        ['nombre' => 'Licenciatura en Producción de Bioimágenes']
    );
}
}
