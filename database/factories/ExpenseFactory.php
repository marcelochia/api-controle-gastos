<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'descricao' => $this->faker->sentence(2),
            'valor' => $this->faker->numerify(),
            'data' => $this->faker->date(),
            'categoria_id' => $this->faker->numberBetween(1,8)
        ];
    }
}
