<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'isbn' => $this->faker->unique()->isbn13(),
            'title' => $this->faker->sentence(),
            'editor' => $this->faker->company(),
            'production' => $this->faker->company(),
            'publish_date' => $this->faker->date(),
            'lending_price' => $this->faker->numberBetween(500, 2500),
            'selling_price' => $this->faker->numberBetween(5000, 20000),
        ];
    }
}
