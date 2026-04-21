<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seeder minimal — comptes de test rapide.
     * Pour un seed complet avec gérants/entreprises/déclarations,
     * utiliser DatabaseSeeder.
     */
    public function run(): void
    {
        // =====================
        // SUPER ADMIN
        // =====================
        $admin = User::firstOrCreate(
            ['email' => 'admin@test.com'],
            [
                'name'     => 'Admin DSI',
                'password' => Hash::make('Admin1234'),
            ]
        );
        $admin->syncRoles('SUPER_ADMIN');

        // =====================
        // AGENT
        // =====================
        $agent = User::firstOrCreate(
            ['email' => 'agent@test.com'],
            [
                'name'     => 'Kouassi Jean-Baptiste',
                'password' => Hash::make('Admin1234'),
            ]
        );
        $agent->syncRoles('AGENT');

        // =====================
        // CONTROLEUR
        // =====================
        $controleur = User::firstOrCreate(
            ['email' => 'controleur@test.com'],
            [
                'name'     => 'Yao Félicité',
                'password' => Hash::make('Admin1234'),
            ]
        );
        $controleur->syncRoles('CONTROLEUR');

        // =====================
        // GERANT
        // =====================
        $gerant = User::firstOrCreate(
            ['email' => 'gerant@test.com'],
            [
                'name'     => 'Coulibaly Seydou',
                'password' => Hash::make('Admin1234'),
            ]
        );
        $gerant->syncRoles('GERANT');
    }
}
