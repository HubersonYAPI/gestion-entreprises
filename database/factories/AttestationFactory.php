<?php

namespace Database\Factories;

use App\Models\Attestation;
use App\Models\Declaration;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attestation>
 */
class AttestationFactory extends Factory
{
    protected $model = Attestation::class;

    public function definition(): array
    {
        return [
            'declaration_id' => Declaration::factory()->valide(),
            'reference'      => 'ATT-' . strtoupper(Str::random(8)),
            // Chemin fictif (Storage::disk('public')) — conforme à TraitementController::terminer()
            'file_path'      => 'attestations/attestation_' . $this->faker->unique()->numberBetween(1000, 9999) . '.pdf',
        ];
    }
}
