<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Entreprise;

class DeclarationFactory extends Factory
{
    /**
     * Compteur statique pour éviter les doublons
     */
    protected static int $counter = 1;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        $prefix = 'DECL';
        $date = now()->format('ym');

        // Génération du numéro unique (0001, 0002, ...)
        $numero = str_pad(self::$counter++, 4, '0', STR_PAD_LEFT);

        return [
            // Relation
            'entreprise_id' => Entreprise::factory(),

            // Référence unique garantie (dans un seul seed)
            'reference' => "$prefix-$date-$numero",

            // Statut & workflow
            'statut' => 'brouillon',
            'phase' => 1,

            // Infos métier
            'nature_activite' => $this->faker->word(),
            'secteur_activite' => $this->faker->word(),
            'produits' => $this->faker->sentence(),

            // Effectifs
            'effectifs' => $this->faker->numberBetween(1, 100),

            // Dates
            'submitted_at' => null,
            'validated_at' => null,
            'date_limite_paiement' => null,
            'paid_at' => null,
            'processed_at' => null,
            'completed_at' => null,
        ];
    }
}