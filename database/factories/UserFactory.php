<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    private static array $nomsIvoiriens = [
        'Koné Amara', 'Coulibaly Seydou', 'Traoré Moussa', 'Diallo Ibrahima',
        'Ouattara Drissa', 'Bamba Adama', 'Touré Boubacar', 'Konaté Mamadou',
        'Kouadio Koffi', 'Yao Kouamé', 'Amon N\'Guessan', 'Aké Affoué',
        'Brou Amenan', 'Aka Adjoua', 'Kouassi Akissi', 'Dembélé Fatoumata',
        'Sanogo Mariam', 'Fofana Rokia', 'Keïta Bintou', 'Camara Nana',
    ];

    public function definition(): array
    {
        return [
            'name'              => $this->faker->randomElement(self::$nomsIvoiriens),
            'email'             => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password'          => Hash::make('Admin1234'),
            'remember_token'    => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
