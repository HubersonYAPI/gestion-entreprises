<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Utilise firstOrCreate pour éviter les doublons si le seeder
     * est appelé plusieurs fois.
     */
    public function run(): void
    {
        $roles = ['GERANT', 'AGENT', 'CONTROLEUR', 'SUPER_ADMIN'];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name'       => $role,
                'guard_name' => 'web',
            ]);
        }
    }
}