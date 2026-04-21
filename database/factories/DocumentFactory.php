<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\Declaration;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    /**
     * Les 5 types obligatoires définis dans DeclarationController::submit()
     */
    public const TYPES_OBLIGATOIRES = [
        'RCCM',
        'CC',
        'produits',
        'appareils',
        'formulaire',
    ];

    public function definition(): array
    {
        return [
            'declaration_id' => Declaration::factory(),
            'type'           => $this->faker->randomElement(self::TYPES_OBLIGATOIRES),
            'statut'         => 'en_attente',
            'file_path'      => 'documents/' . $this->faker->uuid() . '.pdf',
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

    /**
     * Crée les 5 documents obligatoires tous validés pour une déclaration donnée.
     * Usage : DocumentFactory::creerDossiersComplets($declaration->id)
     */
    public static function creerDossiersComplets(int $declarationId): void
    {
        foreach (self::TYPES_OBLIGATOIRES as $type) {
            \App\Models\Document::factory()->valide()->create([
                'declaration_id' => $declarationId,
                'type'           => $type,
            ]);
        }
    }
}
