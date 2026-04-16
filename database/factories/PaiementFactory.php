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
    protected $model = Paiement::class;

    public function definition(): array
    {
        return [
            'declaration_id' => Declaration::factory(),
            'montant'        => $this->faker->randomElement([10000, 25000, 50000, 75000, 100000]),
            'reference'      => 'PAY-' . strtoupper(Str::random(8)),
            'statut'         => 'payé',
            'date_paiement'  => now()->subDays(rand(0, 5)),
        ];
    }

    /** Paiement en attente */
    public function enAttente(): static
    {
        return $this->state(fn () => [
            'statut'        => 'en_attente',
            'date_paiement' => null,
        ]);
    }
}
