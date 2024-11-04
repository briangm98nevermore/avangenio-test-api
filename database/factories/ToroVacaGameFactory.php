<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

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
            'api_key'=>Hash::make('nombre'),
            'numeroPropuesto'=>fake()->randomNumber(4,true),
            'numeroIntentos'=>fake()->randomNumber(2,true),
            'estado' => fake()->boolean(),
            'evaluacion'=>fake()->randomFloat(2,20,500),
            'ranking' => fake()->randomFloat(2,20,500)
        ];
    }
}
