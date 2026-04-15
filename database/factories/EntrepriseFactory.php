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
            'nom'              => $this->faker->company(),
            'rccm'             => 'RCCM-CI-' . $this->faker->unique()->numerify('####-#####'),
            'adresse'          => $this->faker->address(),
            'type_entreprise'  => $this->faker->randomElement([
                'SARL', 'SA', 'SAS', 'Entreprise individuelle', 'GIE',
            ]),
            'secteur_activite' => $this->faker->randomElement([
                'Commerce', 'Industrie', 'Éducation', 'BTP',
                'Informatique', 'Santé', 'Tourisme', 'Agriculture',
                'Automobile', 'Communication', 'Service',
            ]),
        ];
    }
}