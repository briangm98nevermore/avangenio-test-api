<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            'token'=>Str::random(32),
            'api_key'=>Hash::make('token'),
            'numeroPropuesto'=>fake()->randomNumber(4,true),
            'numeroIntentos'=>fake()->randomNumber(2,true),
            'estado' => fake()->boolean(),
            'evaluacion'=>fake()->randomFloat(2,20,500),
            'ranking' => fake()->randomFloat(2,20,500)
        ];
    }
}
