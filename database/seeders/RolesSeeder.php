<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['nombre' => 'superadmin']);
        Role::create(['nombre' => 'bedel']);
    }
}
