<?php

namespace Database\Factories;

use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        //$role = ['user', 'user', 'user', 'user', 'admin'];
        return [
            'username' => $this->faker->unique()->userName(),
            'password' => bcrypt('password'), // password
            'role' => $this->faker->randomElement(['user', 'user', 'user', 'user', 'admin']),
            'isEligible' => $this->faker->boolean(90),
            'client_id' => function (){return Client::inRandomOrder()->first()->id;},
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
