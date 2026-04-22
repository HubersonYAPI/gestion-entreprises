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

    public function definition(): array
    {
        $prefix = 'DECL';
        $date   = now()->format('ym');
        $numero = str_pad(self::$counter++, 4, '0', STR_PAD_LEFT);

        return [
            'entreprise_id'    => Entreprise::factory(),
            'reference'        => "$prefix-$date-$numero",
            'statut'           => 'brouillon',
            'phase'            => 1,
            'nature_activite'  => $this->faker->randomElement([
                'Commerce général', 'Vente de produits alimentaires',
                'Prestation de services informatiques', 'Import-Export',
                'Travaux de construction', 'Activité de transport',
                'Distribution de matériaux', 'Conseil et formation',
            ]),
            'secteur_activite' => $this->faker->randomElement([
                'Commerce', 'Industrie', 'BTP', 'Informatique',
                'Santé', 'Tourisme', 'Agriculture', 'Transport',
                'Communication', 'Service', 'Éducation', 'Finance',
            ]),
            'produits'  => $this->faker->randomElement([
                'Riz, huile, sucre, produits alimentaires divers',
                'Matériaux de construction : ciment, fer, gravier',
                'Équipements informatiques et accessoires',
                'Véhicules et pièces détachées automobiles',
                'Textiles, prêt-à-porter et chaussures',
                'Produits pharmaceutiques et parapharmaceutiques',
                'Mobilier de bureau et fournitures scolaires',
                'Engins agricoles et intrants agricoles',
            ]),
            'effectifs' => $this->faker->numberBetween(1, 200),
            'commentaire'           => null,
            'submitted_at'          => null,
            'validated_at'          => null,
            'date_limite_paiement'  => null,
            'paid_at'               => null,
            'processed_at'          => null,
            'completed_at'          => null,
        ];
    }

    // ── States ──────────────────────────────────────────────────

    /** Phase 2 — Soumis */
    public function soumis(): static
    {
        return $this->state(fn () => [
            'statut'       => 'soumis',
            'phase'        => 2,
            'submitted_at' => now()->subDays(rand(2, 8)),
        ]);
    }

    /** Phase 3 — En attente de paiement (après approbation documents) */
    public function enAttentePaiement(): static
    {
        return $this->state(fn () => [
            'statut'               => 'approuve',
            'phase'                => 3,
            'submitted_at'         => now()->subDays(rand(6, 12)),
            'validated_at'         => now()->subDays(rand(2, 5)),
            'date_limite_paiement' => now()->addHours(rand(12, 48)),
        ]);
    }

    /** Phase 4 — Payé / En traitement */
    public function enTraitement(): static
    {
        return $this->state(fn () => [
            'statut'               => 'en_traitement',
            'phase'                => 4,
            'submitted_at'         => now()->subDays(rand(8, 15)),
            'validated_at'         => now()->subDays(rand(5, 8)),
            'date_limite_paiement' => now()->subDays(rand(1, 4)),
            'paid_at'              => now()->subDays(rand(1, 3)),
            'processed_at'         => now()->subDays(rand(0, 1)),
        ]);
    }

    /** Phase 5 — Validé (terminé, attestation générée) */
    public function valide(): static
    {
        return $this->state(fn () => [
            'statut'               => 'valide',
            'phase'                => 5,
            'submitted_at'         => now()->subDays(rand(10, 20)),
            'validated_at'         => now()->subDays(rand(7, 10)),
            'date_limite_paiement' => now()->subDays(rand(4, 6)),
            'paid_at'              => now()->subDays(rand(3, 5)),
            'processed_at'         => now()->subDays(rand(2, 4)),
            'completed_at'         => now()->subDays(rand(0, 2)),
        ]);
    }

    /** Phase 2 — Rejeté */
    public function rejete(): static
    {
        return $this->state(fn () => [
            'statut'       => 'rejete',
            'phase'        => 2,
            'submitted_at' => now()->subDays(rand(3, 10)),
            'commentaire'  => $this->faker->randomElement([
                'Documents illisibles ou incomplets.',
                'Le RCCM fourni ne correspond pas à l\'entreprise déclarée.',
                'La carte de contribuable est expirée.',
                'Les produits déclarés ne correspondent pas au secteur d\'activité.',
                'Formulaire de déclaration mal rempli.',
            ]),
        ]);
    }

    /** Phase 3 — Délai de paiement expiré */
    public function expire(): static
    {
        return $this->state(fn () => [
            'statut'               => 'rejete',
            'phase'                => 3,
            'submitted_at'         => now()->subDays(rand(10, 20)),
            'validated_at'         => now()->subDays(rand(7, 10)),
            'date_limite_paiement' => now()->subDays(rand(1, 5)),
        ]);
    }
}
