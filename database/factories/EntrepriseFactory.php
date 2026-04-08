<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Gerant;

class EntrepriseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'gerant_id' => Gerant::factory(),
            'nom' => $this->faker->company(),
            'rccm' => 'RCCM-' . $this->faker->unique()->numberBetween(1000, 9999),
            'adresse' => $this->faker->address(),
            'type_entreprise' => $this->faker->randomElement(['SARL', 'SA', 'Entreprise individuelle']),
            'secteur_activite' => $this->faker->randomElement(['Commerce', 'Industrie', 'Education', 'BTP',
            'Informatique', 'Sport', 'Santé', 'Tourisme', 'Automobile', 'Agriculture', 'Communication', 'Service']),
        ];
    }
}