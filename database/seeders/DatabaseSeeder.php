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

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ==============================
        // ROLES
        // ==============================
        $roles = ['GERANT', 'AGENT', 'CONTROLEUR', 'SUPER_ADMIN'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // ==============================
        // SUPER ADMIN
        // ==============================
        $admin = User::factory()->create([
            'name'  => 'Admin DSI',
            'email' => 'admin@dsi.com',
            'password' => Hash::make('Admin1234'),
        ]);
        $admin->assignRole('SUPER_ADMIN');

        // ==============================
        // AGENTS (3)
        // ==============================
        for ($i = 1; $i <= 3; $i++) {
            $user = User::factory()->create([
                'name'  => "Agent $i",
                'email' => "agent$i@test.com",
                'password' => Hash::make('Admin1234'),
            ]);
            $user->assignRole('AGENT');
        }

        // ==============================
        // CONTROLEURS (2)
        // ==============================
        for ($i = 1; $i <= 2; $i++) {
            $user = User::factory()->create([
                'name'  => "Contrôleur $i",
                'email' => "controleur$i@test.com",
                'password' => Hash::make('Admin1234'),
            ]);
            $user->assignRole('CONTROLEUR');
        }

        // ==============================
        // GERANTS (3)
        // ==============================
        $gerants = collect();

        for ($i = 1; $i <= 3; $i++) {
            $user = User::factory()->create([
                'name'  => "Gérant $i",
                'email' => "gerant$i@test.com",
                'password' => Hash::make('Admin1234'),
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

        // Gérant 1 : 4 entreprises garanties
        $gerant1 = $gerants->first();
        for ($i = 0; $i < 4; $i++) {
            $entreprises->push(
                Entreprise::factory()->create(['gerant_id' => $gerant1->id])
            );
        }

        // Gérants 2 & 3 : 3 entreprises chacun
        foreach ($gerants->skip(1) as $gerant) {
            for ($i = 0; $i < 3; $i++) {
                $entreprises->push(
                    Entreprise::factory()->create(['gerant_id' => $gerant->id])
                );
            }
        }

        // ==============================
        // DECLARATIONS — gérants 2 & 3
        // (statuts variés, ~10 déclarations)
        // ==============================
        $autresEntreprises = $entreprises->where('gerant_id', '!=', $gerant1->id)->values();

        foreach ($autresEntreprises as $entreprise) {
            $nb = rand(1, 2);
            for ($i = 0; $i < $nb; $i++) {
                $this->creerDeclaration($entreprise->id, $this->statutAleatoire());
            }
        }

        // ==============================
        // 15 DECLARATIONS — GERANT 1
        // Répartition garantie sur les 5 statuts
        // ==============================
        $entreprisesGerant1 = $entreprises->where('gerant_id', $gerant1->id)->values();

        /*
         * Distribution cible :
         *   brouillon              → 4
         *   soumis                 → 3
         *   en_attente_paiement    → 3
         *   validé                 → 3
         *   rejeté                 → 2
         *                 TOTAL = 15
         */
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

    // ──────────────────────────────────────────
    // HELPERS
    // ──────────────────────────────────────────

    /**
     * Crée une déclaration avec les dates et paiement cohérents selon le statut.
     */
    private function creerDeclaration(int $entrepriseId, string $statut, ?int $phase = null): Declaration
    {
        // Phase déduite du statut si non fournie
        $phase = $phase ?? $this->phaseDepuisStatut($statut);

        $data = [
            'entreprise_id' => $entrepriseId,
            'statut'        => $statut,
            'phase'         => $phase,
            // Les dates submitted_at / validated_at / etc. sont null par défaut dans la factory
        ];

        // Ajout des dates selon l'avancement
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

        // Créer le paiement si la déclaration est validée
        if ($statut === 'validé') {
            Paiement::factory()->create([
                'declaration_id' => $declaration->id,
                'statut'         => 'payé',
                'date_paiement'  => $declaration->paid_at,
            ]);
        }

        return $declaration;
    }

    /**
     * Retourne un statut aléatoire parmi les 5 possibles.
     */
    private function statutAleatoire(): string
    {
        return collect([
            'brouillon',
            'soumis',
            'en_attente_paiement',
            'validé',
            'rejeté',
        ])->random();
    }

    /**
     * Déduit la phase depuis le statut.
     */
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