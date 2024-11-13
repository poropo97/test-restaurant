<?php
namespace App\UseCases;

use App\Models\Recipe;
use App\Models\Ingredient;

class AddRecipe
{
    public function execute($data)
    {
        $recipe = Recipe::create([
            'name' => $data['name'],
            'sale_price' => $data['sale_price'],
        ]);

        $totalCost = 0;
        foreach ($data['ingredients'] as $ingredientData) {
            $ingredient = Ingredient::find($ingredientData['id']);

            if (!$ingredient) {
                throw new \Exception("El ingrediente con ID {$ingredientData['id']} no existe.");
            }

            $cost = $ingredient->cost * $ingredientData['quantity'];
            $totalCost += $cost;
            $recipe->ingredients()->attach($ingredient, ['quantity' => $ingredientData['quantity']]);
        }

        $recipe->update(['cost' => $totalCost]);

        return $recipe;
    }

    public function getCostAnalysis()
    {
        $recipes = Recipe::all();
        
        $highestCostRecipe = $recipes->sortByDesc('cost')->first();
        $lowestCostRecipe = $recipes->sortBy('cost')->first();
        
        $highestMarginRecipe = $recipes->sortByDesc(fn($recipe) => $recipe->getMargin())->first();
        $lowestMarginRecipe = $recipes->sortBy(fn($recipe) => $recipe->getMargin())->first();

        return [
            'highest_cost' => [$highestCostRecipe->name, $highestCostRecipe->cost],
            'lowest_cost' => [$lowestCostRecipe->name, $lowestCostRecipe->cost],
            'highest_margin' => [$highestMarginRecipe->name, $highestMarginRecipe->getMargin()],
            'lowest_margin' => [$lowestMarginRecipe->name, $lowestMarginRecipe->getMargin()],
        ];
    }
}
