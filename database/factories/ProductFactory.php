<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'descripcion' => $this->faker->sentence(3),
            'ean' => $this->faker->ean13(),
            'codigo' => strtoupper($this->faker->bothify('??###')),
        ];
    }
}

