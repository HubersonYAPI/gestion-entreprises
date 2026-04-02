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
     */
    public function run(): void
    {
        // =====================
        // ADMIN
        // =====================
        $admin = User::create([
            'name' => 'Admin DSI',
            'email' => 'admin@dsi.com',
            'password' => Hash::make('Admin1234'),
        ]);

        $admin->assignRole('SUPER_ADMIN');

        // =====================
        // AGENT
        // =====================
        $agent = User::create([
            'name' => 'Agent Industrie',
            'email' => 'agent@ministere.com',
            'password' => Hash::make('Agent1234'),
        ]);

        $agent->assignRole('AGENT');

        // =====================
        // GERANT
        // =====================
        $gerant = User::create([
            'name' => 'Gerant Entreprise',
            'email' => 'gerant@entreprise.com',
            'password' => Hash::make('Gerant1234'),
        ]);

        $gerant->assignRole('GERANT');
    }
}
