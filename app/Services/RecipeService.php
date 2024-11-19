<?php


namespace App\Services;

use App\Services\RecipeUtilityService;
use App\Models\Recipe;
use App\Models\Escandallo;



/*
* En ésta clase encontramos los casos de uso relacionados con las recetas y las ventas.
* Por ejemplo, crear una receta, obtener el análisis de costes, etc.
* Estos "casos de uso" utilizan funciones que éstas podrán ser re-utilizadas en otros 
* casos de uso relacionados con las recetas o todo aquel caso de uso que pueda envolver a éste servicio
*/
class RecipeService extends RecipeUtilityService
{
    protected $recipe;

    public function __construct(Recipe $recipe = null) 
    {
        parent::__construct(recipe: $recipe);
    }
    

    /*
    * Método para crear una receta
    * @param string $name
    * @param float $salePrice
    * @param Escandallo[] $ingredients 
    */
    public function createRecipe(String $name, Float $salePrice, Array $ingredients)
    {
        // validamos ingredientes
        $this->validateIngredients($ingredients);
        // creamos la receta
        $this->createRecipeData($name, $salePrice, $ingredients);

    }

    /*
    * 
    */
    public function getCostAnalysis()
    {
        // 
    }
}