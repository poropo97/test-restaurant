<?php


namespace App\Services\Recipe;

use App\Services\Recipe\RecipeUtilityService;
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
        // 
        // $this->recipe->fresh();
        // 
        return $this->recipe;
    }

    /*  Devolver la receta con el coste más elevado junto con su valor.
     *   Devolver la receta con el coste más bajo junto con su valor.
     *   Devolver la receta con el margen de beneficio más alto junto con su valor. Devolver la receta con el margen de beneficio más bajo junto con su valor.
    * 
    */
    public function getMargenes(\Illuminate\Database\Eloquent\Builder $recetas = null)
    {

        // En caso de que no se haya pasado ninguna receta, se obtienen todas las recetas
        $recetas = $recetas ?: Recipe::query();
        $recetas = $recetas->get();
        // calculamos los márgenes
        $recetas = $this->calcMargenes($recetas);

        // dd($recetas->toArray());
        // devolvemos los resultados
        return [
            'recetaMayorCoste' => $this->getRecetaMayorCoste($recetas)->toArray(),
            'recetaMenorCoste' => $this->getRecetaMenorCoste($recetas)->toArray(),
            'recetaMayorMargen' => $this->getrecetaMayorMargen($recetas)->toArray(),
            'recetaMenorMargen' => $this->getrecetaMenorMargen($recetas)->toArray()
        ];


        
    }
}