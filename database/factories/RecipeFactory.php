<?php

namespace Database\Factories;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipeFactory extends Factory
{
    protected $model = Recipe::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'sale_price' => $this->faker->randomFloat(2, 1, 100),
            'cost' => $this->faker->randomFloat(2, 1, 100),
        ];
    }
}