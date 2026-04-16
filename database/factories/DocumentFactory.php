<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\Declaration;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        return [
            'declaration_id' => Declaration::factory(),

            'type' => $this->faker->randomElement([
                'RCCM',
                'CC',
                'produits',
                'appareils',
                'formulaire',
            ]),

            // Statut par défaut
            'statut' => 'en_attente',

            // Chemin fictif (pas de vrai fichier requis pour les tests)
            'file_path' => 'documents/' . $this->faker->uuid() . '.pdf',
        ];
    }

    /** Document validé */
    public function valide(): static
    {
        return $this->state(fn () => ['statut' => 'valide']);
    }

    /** Document rejeté */
    public function rejete(): static
    {
        return $this->state(fn () => ['statut' => 'rejete']);
    }
}
