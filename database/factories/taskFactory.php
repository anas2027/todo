<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 */
class taskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'=> $this->faker->title(10),
            'endDate'=> $this->faker->dateTime(),
            'users_id'=> $this->faker->numberBetween(1,10),
        ];
    }
}
