<?php

/*
|--------------------------------------------------------------------------
| database/factories/DocumentFactory.php
|--------------------------------------------------------------------------
| Permet de créer de faux documents pour les tests avec :
|   Document::factory()->create([...])
|
| IMPORTANT : copiez ce fichier dans database/factories/DocumentFactory.php
|--------------------------------------------------------------------------
*/

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
            // Lié à une déclaration existante (créée automatiquement si absente)
            'declaration_id' => Declaration::factory(),

            // Un des 5 types valides de votre application
            'type' => $this->faker->randomElement([
                'RCCM',
                'CC',
                'produits',
                'appareils',
                'formulaire',
            ]),

            // Statut par défaut : en attente de validation
            'statut' => 'en_attente',

            // Chemin fictif vers un fichier (pas besoin d'un vrai fichier pour les tests)
            'file_path' => 'documents/' . $this->faker->uuid() . '.pdf',
        ];
    }
}
