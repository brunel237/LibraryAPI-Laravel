<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        //$gender = ['male', 'female'];
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'sex' => $this->faker->randomElement(['male', 'female']),
            'date_of_birth' => $this->faker->date(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => bcrypt('password'),
            'phone_number' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'profession' => $this->faker->jobTitle(),
        ];
    }
}
