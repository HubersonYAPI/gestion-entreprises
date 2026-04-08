<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Gerant;
use App\Models\Entreprise;
use App\Models\Declaration;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // ==============================
        // ROLES
        // ==============================
        $roles = ['GERANT', 'AGENT', 'CONTROLEUR', 'SUPER_ADMIN'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // ==============================
        // AGENTS
        // ==============================
        for ($i = 1; $i <= 3; $i++) {
            $user = User::factory()->create([
                'email' => "agent$i@test.com",
                'password' => bcrypt('Admin1234'),
            ]);

            $user->assignRole('AGENT');
        }

        // ==============================
        // CONTROLEURS
        // ==============================
        for ($i = 1; $i <= 2; $i++) {
            $user = User::factory()->create([
                'email' => "controleur$i@test.com",
                'password' => bcrypt('Admin1234'),
            ]);

            $user->assignRole('CONTROLEUR');
        }

        // ==============================
        // GERANTS (3)
        // ==============================
        $gerants = collect();

        for ($i = 1; $i <= 3; $i++) {

            $user = User::factory()->create([
                'name' => "Gerant $i",
                'email' => "gerant$i@test.com",
                'password' => bcrypt('Admin1234'),
            ]);

            $user->assignRole('GERANT');

            $gerant = Gerant::factory()->create([
                'user_id' => $user->id,
            ]);

            $gerants->push($gerant);
        }

        // ==============================
        // ENTREPRISES (10)
        // ==============================
        $entreprises = collect();

        for ($i = 0; $i < 10; $i++) {

            $gerant = $gerants->random();

            $entreprise = Entreprise::factory()->create([
                'gerant_id' => $gerant->id,
            ]);

            $entreprises->push($entreprise);
        }

        // ==============================
        // DECLARATIONS (25)
        // ==============================
        foreach ($entreprises as $entreprise) {

            $nb = rand(2, 4); // chaque entreprise a plusieurs déclarations

            for ($i = 0; $i < $nb; $i++) {

                Declaration::factory()->create([
                    'entreprise_id' => $entreprise->id,
                    'statut' => 'brouillon',
                    'phase' => 1,
                ]);
            }
        }

        // ==============================
        // GARANTIR 15 DECLARATIONS POUR 1 GERANT
        // ==============================
        $gerantCible = $gerants->first();

        $entreprisesGerant = Entreprise::where('gerant_id', $gerantCible->id)->get();

        for ($i = 0; $i < 15; $i++) {
            Declaration::factory()->create([
                'entreprise_id' => $entreprisesGerant->random()->id,
            ]);
        }
    }
}
