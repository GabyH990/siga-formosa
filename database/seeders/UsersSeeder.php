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
        $superAdminRole = Role::where('nombre', 'superadmin')->first();
        $bedelRole = Role::where('nombre', 'bedel')->first();
        $tup = Career::where('codigo', 'TUP')->first();
        $lpb = Career::where('codigo', 'LPB')->first();

        // Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@siga.test',
            'password' => Hash::make('password'),
            'role_id' => $superAdminRole->id,
            'career_id' => null,
        ]);

        // Bedel TUP
        User::create([
            'name' => 'Bedel TUP',
            'email' => 'bedel.tup@siga.test',
            'password' => Hash::make('password'),
            'role_id' => $bedelRole->id,
            'career_id' => $tup->id,
        ]);

        // Bedel LPB
        User::create([
            'name' => 'Bedel LPB',
            'email' => 'bedel.lpb@siga.test',
            'password' => Hash::make('password'),
            'role_id' => $bedelRole->id,
            'career_id' => $lpb->id,
        ]);
    }
}
