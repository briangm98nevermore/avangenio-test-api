<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ToroVacaGame>
 */
class ToroVacaGameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->name(),
            'edad' => fake()->randomNumber(2,true),
            'estado' => fake()->numberBetween(0,1),
            'ranking' => fake()->randomFloat(2,20,500)
        ];
    }
}
