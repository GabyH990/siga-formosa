<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            //CareersSeeder::class,
            //UsersSeeder::class,
            //CarrerasMateriasComisionesSeeder::class,
            //StudentsSeeder::class,

        ]);
    }
}
