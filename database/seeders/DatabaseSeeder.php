<?php

namespace Database\Seeders;

use App\Models\Attestation;
use App\Models\Declaration;
use App\Models\Document;
use App\Models\Entreprise;
use App\Models\Gerant;
use App\Models\Paiement;
use App\Models\User;
use Database\Factories\DocumentFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * Statuts produits dans ce seeder :
     *   brouillon            phase 1
     *   soumis               phase 2
     *   approuve             phase 3
     *   paye                 phase 4
     *   en_traitement        phase 4
     *   valide               phase 5 + paiement + attestation
     *   rejete               phase 2
     */
    public function run(): void
    {
        // ==============================
        // ROLES
        // ==============================
        $this->call(RoleSeeder::class);

        // ==============================
        // SUPER ADMIN
        // ==============================
        $admin = User::factory()->create([
            'name'     => 'Admin DSI',
            'email'    => 'admin@dsi.com',
            'password' => Hash::make('Admin1234'),
        ]);
        $admin->assignRole('SUPER_ADMIN');

        // ==============================
        // AGENTS (3)
        // ==============================
        $agentNoms = [
            ['name' => 'Kouassi Jean-Baptiste', 'email' => 'agent1@test.com'],
            ['name' => 'Traoré Aminata',        'email' => 'agent2@test.com'],
            ['name' => 'Brou Hervé',             'email' => 'agent3@test.com'],
        ];

        foreach ($agentNoms as $data) {
            $user = User::factory()->create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make('Admin1234'),
            ]);
            $user->assignRole('AGENT');
        }

        // ==============================
        // CONTROLEURS (2)
        // ==============================
        $controleurNoms = [
            ['name' => 'Yao Félicité',     'email' => 'controleur1@test.com'],
            ['name' => 'Koné Dramane',     'email' => 'controleur2@test.com'],
        ];

        foreach ($controleurNoms as $data) {
            $user = User::factory()->create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make('Admin1234'),
            ]);
            $user->assignRole('CONTROLEUR');
        }

        // ==============================
        // GERANTS (4)
        // Gérant principal = gerant1 (test principal)
        // ==============================
        $gerantComptes = [
            ['name' => 'N\'Guessan Ama',    'email' => 'gerant1@test.com'],
            ['name' => 'Dembélé Fatoumata', 'email' => 'gerant2@test.com'],
            ['name' => 'Konaté Ibrahima',   'email' => 'gerant3@test.com'],
        ];

        $gerants = collect();

        foreach ($gerantComptes as $data) {
            $user = User::factory()->create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make('Admin1234'),
            ]);
            $user->assignRole('GERANT');

            $gerant = Gerant::factory()->create(['user_id' => $user->id]);
            $gerants->push($gerant);
        }

        $gerant1 = $gerants->first(); // Gérant principal pour les tests

        // ==============================
        // ENTREPRISES
        // Gérant 1 → 4 entreprises
        // Autres gérants → 2 entreprises chacun
        // ==============================
        $entreprisesGerant1 = collect();

        for ($i = 0; $i < 3; $i++) {
            $entreprisesGerant1->push(
                Entreprise::factory()->create(['gerant_id' => $gerant1->id])
            );
        }

        $autresEntreprises = collect();
        foreach ($gerants->skip(1) as $gerant) {
            for ($i = 0; $i < 2; $i++) {
                $autresEntreprises->push(
                    Entreprise::factory()->create(['gerant_id' => $gerant->id])
                );
            }
        }

        // ==============================
        // DECLARATIONS — GERANT 1 (20 déclarations)
        // Distribution garantie pour les tests
        //   brouillon            → 4
        //   soumis               → 4
        //   approuve  → 4
        //   valide               → 5  (phase 5 + paiement + attestation)
        //   rejete               → 3
        //                 TOTAL  = 20
        // ==============================
        $distribution = [
            ['statut' => 'brouillon',           'nb' => 4],
            ['statut' => 'soumis',              'nb' => 4],
            ['statut' => 'approuve',            'nb' => 4],
            ['statut' => 'valide',              'nb' => 5],
            ['statut' => 'rejete',              'nb' => 3],
        ];

        foreach ($distribution as $groupe) {
            for ($i = 0; $i < $groupe['nb']; $i++) {
                $this->creerDeclaration(
                    $entreprisesGerant1->random()->id,
                    $groupe['statut']
                );
            }
        }

        // ==============================
        // DECLARATIONS — AUTRES GERANTS (~10 déclarations)
        // ==============================
        foreach ($autresEntreprises as $entreprise) {
            $nb = rand(1, 2);
            for ($i = 0; $i < $nb; $i++) {
                $this->creerDeclaration(
                    $entreprise->id,
                    $this->statutAleatoire()
                );
            }
        }
    }

    // ──────────────────────────────────────────
    // HELPERS
    // ──────────────────────────────────────────

    /**
     * Crée une déclaration complète avec documents, paiement et attestation
     * selon le statut demandé.
     */
    private function creerDeclaration(int $entrepriseId, string $statut): Declaration
    {
        $data = ['entreprise_id' => $entrepriseId];

        $declaration = match ($statut) {
            'soumis'              => Declaration::factory()->soumis()->create($data),
            'approuve'            => Declaration::factory()->enAttentePaiement()->create($data),
            'en_traitement'       => Declaration::factory()->enTraitement()->create($data),
            'valide'              => Declaration::factory()->valide()->create($data),
            'rejete'              => Declaration::factory()->rejete()->create($data),
            'rejete'              => Declaration::factory()->expire()->create($data),
            default               => Declaration::factory()->create($data), // brouillon
        };

        // ── Documents ────────────────────────────────────────────
        // À partir de "soumis", les 5 documents obligatoires existent
        if (in_array($statut, ['soumis', 'approuve', 'en_traitement', 'valide', 'rejete'])) {
            $this->creerDocuments($declaration, $statut);
        }

        // ── Paiement ─────────────────────────────────────────────
        if (in_array($statut, ['en_traitement', 'valide'])) {
            Paiement::factory()->create([
                'declaration_id' => $declaration->id,
                'statut'         => 'paye',
                'date_paiement'  => $declaration->paid_at ?? now()->subDays(rand(1, 3)),
            ]);
        }

        // ── Attestation (phase 5 = valide uniquement) ─────────────
        if ($statut === 'valide') {
            Attestation::factory()->create([
                'declaration_id' => $declaration->id,
                'file_path'      => 'attestations/attestation_' . $declaration->id . '.pdf',
            ]);
        }

        return $declaration;
    }

    /**
     * Crée les documents d'une déclaration selon son statut.
     * - valide          → tous les 5 documents validés
     * - approuve / en_traitement → tous validés
     * - soumis          → documents en attente (agent n'a pas encore statué)
     * - rejete          → documents avec au moins un rejeté
     */
    private function creerDocuments(Declaration $declaration, string $statut): void
    {
        $types = ['RCCM', 'CC', 'produits', 'appareils', 'formulaire'];

        foreach ($types as $type) {
            $docStatut = match ($statut) {
                'valide', 'approuve', 'en_traitement' => 'valide',
                'rejete'  => ($type === 'RCCM' ? 'rejete' : 'valide'), // au moins 1 rejeté
                default   => 'en_attente',
            };

            Document::factory()->create([
                'declaration_id' => $declaration->id,
                'type'           => $type,
                'statut'         => $docStatut,
            ]);
        }
    }

    /**
     * Statut aléatoire parmi ceux souhaités dans le seeder.
     */
    private function statutAleatoire(): string
    {
        return collect([
            'brouillon',
            'soumis',
            'approuve',
            'paye',
            'en_traitement',
            'valide',
            'rejete',
        ])->random();
    }
}
