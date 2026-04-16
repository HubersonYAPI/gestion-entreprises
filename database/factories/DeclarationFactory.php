<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Entreprise;

class DeclarationFactory extends Factory
{
    /**
     * Compteur statique pour garantir des références uniques par run.
     */
    protected static int $counter = 1;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $prefix = 'DECL';
        $date   = now()->format('ym');   // ex: 2604
        $numero = str_pad(self::$counter++, 4, '0', STR_PAD_LEFT);

        return [
            // Relation
            'entreprise_id' => Entreprise::factory(),

            // Référence unique (réinitialisée à chaque php artisan db:seed)
            'reference' => "$prefix-$date-$numero",

            // Statut & workflow — valeurs par défaut (brouillon / phase 1)
            'statut' => 'brouillon',
            'phase'  => 1,

            // Infos métier
            'nature_activite'  => $this->faker->word(),
            'secteur_activite' => $this->faker->randomElement([
                'Commerce', 'Industrie', 'Éducation', 'BTP',
                'Informatique', 'Santé', 'Tourisme', 'Agriculture',
                'Communication', 'Service',
            ]),
            'produits'  => $this->faker->sentence(),
            'effectifs' => $this->faker->numberBetween(1, 200),

            // Toutes les dates nullable par défaut
            'submitted_at'          => null,
            'validated_at'          => null,
            'date_limite_paiement'  => null,
            'paid_at'               => null,
            'processed_at'          => null,
            'completed_at'          => null,
        ];
    }

    // ── States pratiques ──────────────────────────────────────

    /** Déclaration soumise (phase 2) */
    public function soumis(): static
    {
        return $this->state(fn () => [
            'statut'       => 'soumis',
            'phase'        => 2,
            'submitted_at' => now()->subDays(rand(2, 8)),
        ]);
    }

    /** En attente de paiement (phase 3) */
    public function enAttentePaiement(): static
    {
        return $this->state(fn () => [
            'statut'                => 'en_attente_paiement',
            'phase'                 => 3,
            'submitted_at'          => now()->subDays(rand(5, 12)),
            'validated_at'          => now()->subDays(rand(1, 4)),
            'date_limite_paiement'  => now()->addDays(rand(3, 7)),
        ]);
    }

    /** Validée (phase 4) */
    public function valide(): static
    {
        return $this->state(fn () => [
            'statut'                => 'valide',
            'phase'                 => 4,
            'submitted_at'          => now()->subDays(rand(8, 15)),
            'validated_at'          => now()->subDays(rand(4, 7)),
            'date_limite_paiement'  => now()->subDays(rand(1, 3)),
            'paid_at'               => now()->subDays(rand(0, 2)),
            'completed_at'          => now()->subDays(rand(0, 1)),
        ]);
    }

    /** Rejetée (phase 2) */
    public function rejete(): static
    {
        return $this->state(fn () => [
            'statut'       => 'rejete',
            'phase'        => 2,
            'submitted_at' => now()->subDays(rand(3, 10)),
            'processed_at' => now()->subDays(rand(1, 3)),
        ]);
    }
}
