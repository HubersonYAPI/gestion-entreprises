<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class GerantFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'        => User::factory(),
            'nom'            => fake()->lastName(),
            'prenoms'        => fake()->firstName(),
            'contact'        => fake()->phoneNumber(),
            'piece_identite' => 'documents/cni_placeholder.pdf',
        ];
    }
}