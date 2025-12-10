<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Career;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Obtener Roles y Carreras (Lectura de datos, no inserción)
        $superAdminRole = Role::where('nombre', 'superadmin')->first();
        $bedelRole = Role::where('nombre', 'bedel')->first();
        $tup = Career::where('codigo', 'TUP')->first();
        $lpb = Career::where('codigo', 'LPB')->first();

        // Manejo de roles/carreras no encontradas (opcional, pero buena práctica)
        if (!$superAdminRole || !$bedelRole || !$tup || !$lpb) {
             // Si alguno no se encuentra, detiene el seeder para evitar errores de objetos nulos.
             // Esto requiere que RolesSeeder y CareersSeeder siempre se ejecuten antes.
             throw new \Exception('Missing required roles or careers data. Check preceding Seeders.');
        }

        // --- INSERCIÓN CON firstOrCreate (Idempotencia) ---

        // 1. Super Admin (admin@siga.test)
        User::firstOrCreate(
            ['email' => 'admin@siga.test'], // Criterio de búsqueda (UNIQUE: email)
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
                'role_id' => $superAdminRole->id,
                'career_id' => null,
            ]
        );

        // 2. Bedel TUP (bedel.tup@siga.test)
        User::firstOrCreate(
            ['email' => 'bedel.tup@siga.test'],
            [
                'name' => 'Bedel TUP',
                'password' => Hash::make('password'),
                'role_id' => $bedelRole->id,
                'career_id' => $tup->id,
            ]
        );

        // 3. Bedel LPB (bedel.lpb@siga.test)
        User::firstOrCreate(
            ['email' => 'bedel.lpb@siga.test'],
            [
                'name' => 'Bedel LPB',
                'password' => Hash::make('password'),
                'role_id' => $bedelRole->id,
                'career_id' => $lpb->id,
            ]
        );
    }
}