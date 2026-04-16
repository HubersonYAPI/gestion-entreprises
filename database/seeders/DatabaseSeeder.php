<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Gerant;
use App\Models\Entreprise;
use App\Models\Declaration;
use App\Models\Paiement;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ==============================
        // ROLES (idempotent)
        // ==============================
        $roles = ['GERANT', 'AGENT', 'CONTROLEUR', 'SUPER_ADMIN'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // ==============================
        // SUPER ADMIN
        // ==============================
        $admin = User::firstOrCreate(
            ['email' => 'admin@dsi.com'],
            [
                'name'               => 'Admin DSI',
                'password'           => Hash::make('Admin1234'),
                'email_verified_at'  => now(),
            ]
        );
        $admin->syncRoles(['SUPER_ADMIN']);

        // ==============================
        // AGENTS (3)
        // ==============================
        for ($i = 1; $i <= 3; $i++) {
            $user = User::firstOrCreate(
                ['email' => "agent$i@test.com"],
                [
                    'name'               => "Agent $i",
                    'password'           => Hash::make('Admin1234'),
                    'email_verified_at'  => now(),
                ]
            );
            $user->syncRoles(['AGENT']);
        }

        // ==============================
        // CONTROLEURS (2)
        // ==============================
        for ($i = 1; $i <= 2; $i++) {
            $user = User::firstOrCreate(
                ['email' => "controleur$i@test.com"],
                [
                    'name'               => "Contrôleur $i",
                    'password'           => Hash::make('Admin1234'),
                    'email_verified_at'  => now(),
                ]
            );
            $user->syncRoles(['CONTROLEUR']);
        }

        // ==============================
        // GERANTS (3)
        // ==============================
        $gerants = collect();

        for ($i = 1; $i <= 3; $i++) {
            $user = User::firstOrCreate(
                ['email' => "gerant$i@test.com"],
                [
                    'name'               => "Gérant $i",
                    'password'           => Hash::make('Admin1234'),
                    'email_verified_at'  => now(),
                ]
            );
            $user->syncRoles(['GERANT']);

            // Gerant lié — firstOrCreate pour l'idempotence
            $gerant = Gerant::firstOrCreate(['user_id' => $user->id]);
            $gerants->push($gerant);
        }

        // ==============================
        // ENTREPRISES (10) — idempotentes via factory uniqueFor
        // On ne recrée pas si le gerant a déjà des entreprises.
        // ==============================
        $entreprises = collect();
        $gerant1     = $gerants->first();

        // Gérant 1 : 4 entreprises
        $existing1 = Entreprise::where('gerant_id', $gerant1->id)->get();
        $toCreate1 = max(0, 4 - $existing1->count());
        for ($i = 0; $i < $toCreate1; $i++) {
            $entreprises->push(Entreprise::factory()->create(['gerant_id' => $gerant1->id]));
        }
        $entreprises = $entreprises->merge($existing1);

        // Gérants 2 & 3 : 3 entreprises chacun
        foreach ($gerants->skip(1) as $gerant) {
            $existing = Entreprise::where('gerant_id', $gerant->id)->get();
            $toCreate = max(0, 3 - $existing->count());
            for ($i = 0; $i < $toCreate; $i++) {
                $entreprises->push(Entreprise::factory()->create(['gerant_id' => $gerant->id]));
            }
            $entreprises = $entreprises->merge($existing);
        }

        // ==============================
        // DECLARATIONS — Gérants 2 & 3
        // On ne crée que si pas encore de déclarations
        // ==============================
        $autresEntreprises = $entreprises->where('gerant_id', '!=', $gerant1->id)->values();
        foreach ($autresEntreprises as $entreprise) {
            if (Declaration::where('entreprise_id', $entreprise->id)->exists()) {
                continue; // déjà seeded
            }
            $nb = rand(1, 2);
            for ($i = 0; $i < $nb; $i++) {
                $this->creerDeclaration($entreprise->id, $this->statutAleatoire());
            }
        }

        // ==============================
        // 15 DECLARATIONS — GERANT 1
        // ==============================
        $entreprisesGerant1 = $entreprises->where('gerant_id', $gerant1->id)->values();

        // Ne crée les déclarations que si pas encore présentes
        $declExistantes = Declaration::whereIn('entreprise_id', $entreprisesGerant1->pluck('id'))->count();
        if ($declExistantes < 15) {
            $distribution = [
                ['statut' => 'brouillon',           'phase' => 1, 'nb' => 4],
                ['statut' => 'soumis',              'phase' => 2, 'nb' => 3],
                ['statut' => 'en_attente_paiement', 'phase' => 3, 'nb' => 3],
                ['statut' => 'validé',              'phase' => 4, 'nb' => 3],
                ['statut' => 'rejeté',              'phase' => 2, 'nb' => 2],
            ];

            foreach ($distribution as $groupe) {
                for ($i = 0; $i < $groupe['nb']; $i++) {
                    $this->creerDeclaration(
                        $entreprisesGerant1->random()->id,
                        $groupe['statut'],
                        $groupe['phase']
                    );
                }
            }
        }
    }

    // ──────────────────────────────────────────
    // HELPERS
    // ──────────────────────────────────────────

    private function creerDeclaration(int $entrepriseId, string $statut, ?int $phase = null): Declaration
    {
        $phase = $phase ?? $this->phaseDepuisStatut($statut);

        $data = [
            'entreprise_id' => $entrepriseId,
            'statut'        => $statut,
            'phase'         => $phase,
        ];

        if (in_array($statut, ['soumis', 'en_attente_paiement', 'validé', 'rejeté'])) {
            $data['submitted_at'] = now()->subDays(rand(3, 10));
        }

        if (in_array($statut, ['en_attente_paiement', 'validé'])) {
            $data['validated_at']         = now()->subDays(rand(1, 4));
            $data['date_limite_paiement'] = now()->addDays(rand(3, 7));
        }

        if ($statut === 'validé') {
            $data['paid_at']      = now()->subDays(rand(0, 2));
            $data['completed_at'] = now()->subDays(rand(0, 1));
        }

        if ($statut === 'rejeté') {
            $data['processed_at'] = now()->subDays(rand(1, 3));
        }

        $declaration = Declaration::factory()->create($data);

        if ($statut === 'validé') {
            Paiement::factory()->create([
                'declaration_id' => $declaration->id,
                'statut'         => 'payé',
                'date_paiement'  => $declaration->paid_at,
            ]);
        }

        return $declaration;
    }

    private function statutAleatoire(): string
    {
        return collect(['brouillon', 'soumis', 'en_attente_paiement', 'validé', 'rejeté'])->random();
    }

    private function phaseDepuisStatut(string $statut): int
    {
        return match ($statut) {
            'brouillon'           => 1,
            'soumis'              => 2,
            'rejeté'              => 2,
            'en_attente_paiement' => 3,
            'validé'              => 4,
            default               => 1,
        };
    }
}