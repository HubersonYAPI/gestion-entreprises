<?php

namespace Database\Factories;

use App\Models\Paiement;
use App\Models\Declaration;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Paiement>
 */
class PaiementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'declaration_id' => Declaration::factory(),
            'montant' => 10000,
            'reference' => 'PAY-' . strtoupper(Str::random(8)),
            'statut' => 'payé',
            'date_paiement' => now(),
        ];
    }
}
