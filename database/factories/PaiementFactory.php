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
            // Montant fixe de 10 000 FCFA comme dans PaiementController::payer()
            'montant'        => 10000,
            'reference'      => 'PAY-' . strtoupper(Str::random(8)),
            // Statut aligné avec PaiementController (valeur 'paye' sans accent)
            'statut'         => 'paye',
            'date_paiement'  => now()->subDays(rand(0, 5)),
        ];
    }

}
