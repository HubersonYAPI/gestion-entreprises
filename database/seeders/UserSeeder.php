<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Utilise firstOrCreate pour éviter les doublons.
     */
    public function run(): void
    {
        // =====================
        // SUPER ADMIN
        // =====================
        $admin = User::firstOrCreate(
            ['email' => 'admin@dsi.com'],
            [
                'name'     => 'Admin DSI',
                'password' => Hash::make('Admin1234'),
            ]
        );
        $admin->assignRole('SUPER_ADMIN');

        // =====================
        // AGENT
        // =====================
        $agent = User::firstOrCreate(
            ['email' => 'agent@ministere.com'],
            [
                'name'     => 'Agent Industrie',
                'password' => Hash::make('Admin1234'),
            ]
        );
        $agent->assignRole('AGENT');

        // =====================
        // GERANT
        // =====================
        $gerant = User::firstOrCreate(
            ['email' => 'gerant@entreprise.com'],
            [
                'name'     => 'Gérant Entreprise',
                'password' => Hash::make('Admin1234'),
            ]
        );
        $gerant->assignRole('GERANT');
    }
}