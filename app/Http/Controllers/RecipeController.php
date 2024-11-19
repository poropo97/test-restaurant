<?php
namespace App\Http\Controllers;

use App\Services;

class RecipeController extends Controller
{
    public function addRecipe(Request $request, AddRecipe $addRecipe)
    {
        $recipe = $addRecipe->execute($request->all());
        return response()->json($recipe);
    }

    public function analyzeCosts(AddRecipe $addRecipe)
    {
        return response()->json($addRecipe->getCostAnalysis());
    }
}
