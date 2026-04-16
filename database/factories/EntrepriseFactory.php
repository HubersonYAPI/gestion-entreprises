<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Gerant;

class EntrepriseFactory extends Factory
{
    public function definition(): array
    {
        return [
            'gerant_id'        => Gerant::factory(),
            'nom'              => fake()->company(),
            'rccm'             => 'RCCM-CI-' . fake()->unique()->numerify('####-#####'),
            'adresse'          => fake()->address(),
            'type_entreprise'  => fake()->randomElement([
                'SARL', 'SA', 'SAS', 'Entreprise individuelle', 'GIE',
            ]),
            'secteur_activite' => fake()->randomElement([
                'Commerce', 'Industrie', 'Éducation', 'BTP',
                'Informatique', 'Santé', 'Tourisme', 'Agriculture',
                'Automobile', 'Communication', 'Service',
            ]),
        ];
    }
}