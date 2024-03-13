<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\labeltask>
 */
class label_tasksFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
             "task_id"=>$this->faker->numberBetween(0,5),
             "label_id"=>$this->faker->numberBetween(0,5)

        ];
    }
}
