<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Career;

class CareersSeeder extends Seeder
{
    public function run(): void
    {
        Career::create(['nombre' => 'Tecnicatura Universitaria en Programación', 'codigo' => 'TUP']);
        Career::create(['nombre' => 'Licenciatura en Producción de Bioimágenes', 'codigo' => 'LPB']);
    }
}
