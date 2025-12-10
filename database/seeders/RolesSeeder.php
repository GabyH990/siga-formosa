<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        // El primer array [] es el criterio de búsqueda.
        // El segundo array [] (opcional) son los valores adicionales a insertar si la fila no existe.

        // 1. Rol Superadmin
        Role::firstOrCreate(
            ['nombre' => 'superadmin'],
            [
                // Puedes añadir aquí otros campos si son requeridos
                // 'descripcion' => 'Administrador del sistema',
            ]
        );
        
        // 2. Rol Bedel
        Role::firstOrCreate(
            ['nombre' => 'bedel'],
            [
                // 'descripcion' => 'Encargado de bedelía',
            ]
        );
        
        // Si tienes otros roles como 'profesor' o 'alumno', repite el patrón:
        // Role::firstOrCreate(['nombre' => 'profesor']);
    }
}