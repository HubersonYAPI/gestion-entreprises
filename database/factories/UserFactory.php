<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        $faker = Faker::create();
        
        return [
            'name'               => $faker->name(),
            'email'              => $faker->safeEmail(),
            'email_verified_at'  => now(),
            'password'           => Hash::make('Admin1234'),
            'remember_token'     => Str::random(10),
        ];
    }

    /**
     * Email non vérifié.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}