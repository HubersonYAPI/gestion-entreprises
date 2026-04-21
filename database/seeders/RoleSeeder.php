<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Crée les 4 rôles de l'application.
     * firstOrCreate = idempotent, peut être appelé plusieurs fois sans erreur.
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
