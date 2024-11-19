<?php
// app/Services/RecipeUtilityService.php
namespace App\Services\Recipe;

use Illuminate\Support\Collection;
use App\Models\Ingredient;
use App\Models\Escandallo;
use App\Models\Recipe;
use Exception;

/*
* En ésta clase encontramos todas las funciones de utilidad relacionadas con las recetas. 
* Por ejemplo, validar los ingredientes de una receta, crear una receta, etc. Todas las re-utilizables en los casos de uso de recetas
* Yo he utilzado ésta estructura porque para el uso de Laravel es suficiente y sin ser hexagonal es más sencillo de entender o escalar en proyectos pequeños
*/
class RecipeUtilityService
{
    protected $recipe;

    public function __construct(Recipe $recipe = null)
    {
        // si no existe se crea una nueva instancia de Recipe
        $this->recipe = $recipe ?: new Recipe();
    }

    /*
    * Método para validar los ingredientes de una receta
    */
    public function validateIngredients(array $ingredients)
    {
        // que haya al menos un ingrediente
        if (count($ingredients) === 0) {
            throw new Exception("La receta debe tener al menos un ingrediente.");
        }
        // que todos los ingredientes sean instancias de Escandallo
        foreach ($ingredients as $ingredient) {
            if (!$ingredient instanceof Escandallo) {
                throw new Exception("Todos los ingredientes deben ser instancias de Escandallo.");
            }
        }
    }

    public function createRecipeData($name, $salePrice, $ingredients) : Bool
    {
        // creamos la receta
        $this->recipe->name = $name;
        $this->recipe->sale_price = $salePrice;
        $this->recipe->save();
        
        // recorremos los ingredientes y los asociamos a la receta. 
        foreach ($ingredients as $ingredient) {
            // dd($ingredient);
            // si se trata de un ingrediente accedemos al coste del ingrediente
            if (isset($ingredient->ingredient_id)) {
                // dd('ingrediente');
                $escandallo = new Escandallo([
                    'ingredient_id' => $ingredient->ingredient_id,
                    'quantity' => $ingredient->quantity,
                    'recipe_id' => $this->recipe->id,
                ]);
                $escandallo->save();
            } elseif (isset($ingredient->recipe_id)) {
                // obtenemos la receta, sus ingredientes y los asociamos a la receta
                $recipe = Recipe::find($ingredient->recipe_id);
                foreach ($recipe->escandallos as $escandallo) {
                    $escandallo = new Escandallo([
                        'ingredient_id' => $escandallo->ingredient_id,
                        'quantity' => $escandallo->quantity,
                        'recipe_id' => $this->recipe->id,
                    ]);
                    $escandallo->save();
                }
                
            }
            
        }

        // calculamos coste
        $this->recipe->update(['cost' => $this->calculateCostOfRecipe()]);
        // 
        return true;
    }

    public function getRecetaMayorCoste() {
        return $recetaMayorCoste = Recipe::orderBy('cost', 'desc')->first();

    }
    public function getRecetaMenorCoste() {
        return $recetaMenorCoste = Recipe::orderBy('cost', 'asc')->first();
    }


    public function calcMargenes(&$recipes){
        foreach ($recipes as $receta) {
            if ($receta->cost != 0) {
                $receta->margen = (($receta->sale_price - $receta->cost) / $receta->cost * 100)."%"; //
            } else {
                $receta->margen = "0%"; // 
            }
        }
        return $recipes;
    }

    /*
    * Método para obtener la receta con el margen de beneficio más alto a partir de un conjunto de recetas
    */
    public function getRecetaMayorMargen(Collection &$recipes) {
        return $recetaMayorMargen = $recipes->sortByDesc('margen')->first();
    }

    /*
    * Método para obtener la receta con el margen de beneficio más bajo a partir de un conjunto de recetas
    */
    public function getRecetaMenorMargen(Collection &$recipes) {
        return $recetaMenorMargen = $recipes->sortBy('margen')->first();
    }

   

    /*
    * Método para calcular el coste de una receta
    * Esta es una función muy importante ya que el coste de una receta puede variar en el tiempo y es importante tenerlo actualizado
    */
    public function calculateCostOfRecipe($recipe = null, $processedRecipes = []) {
        $recipe = $recipe ?: $this->recipe;
        $totalCost = 0;

        // Evitar loops infinitos <- importante
        if (in_array($recipe->id, $processedRecipes)) {
            return 0;
        }
        $processedRecipes[] = $recipe->id;

        // recorremos los escandallos de una receta y sumamos el coste de cada ingrediente
        foreach ($recipe->escandallos as $escandallo) {
            // si se trata de un ingrediente accedemos al coste del ingrediente
            if ($escandallo->ingredient) {
                $totalCost += ($escandallo->ingredient->cost * $escandallo->quantity);
            } else if ($escandallo->recipe) {
                // si se trata de una receta accedemos al coste de la receta
                $totalCost += $this->calculateCostOfRecipe($escandallo->recipe, $processedRecipes);
            }
        }
        // dd($totalCost);
        // haciendolo así nos ahorramos calcular los costes a través de la relación de ingredientes de la receta y podemos reutilizar la función en otros casos de uso o en caso que se actualizen los costes de los ingredientes
        return $totalCost;
    }




     /* He querido dejar una implentación menos óptima y según he ido haciendo la he re-hecho para que sea más re-utilizable (la de arriba) y con sentido (creando entonces la función de calcularCostes de una receta)
    public function createRecipeData($name, $salePrice, $ingredients) : Recipe
    {
        // creamos la receta
        $this->recipe->create([
            'name' => $name,
            'sale_price' => $salePrice,
        ]);

        $this->recipe->ingredients()->attach($ingredient, ['quantity' => $ingredient->quantity]);


        // recorremos los ingredientes y los asociamos a la receta. 
        // Además calculamos el coste total de la receta
        foreach ($ingredients as $ingredient) {

            if(!$ingredient = Ingredient::find($ingredient->id)){
                throw new Exception("El ingrediente con ID {$ingredient->id} no existe.");
            }
            // 🦉 Aqui en éste caso se calcula el coste al crear la receta, pero al escalar habría que tener en cuenta que el coste de los ingredientes puede variar en un futuro y como afecta a las recetas ya creadas
            $cost = $ingredient->cost * $ingredient->quantity;
            $totalCost += $cost;
            // 
        }

        $this->recipe->update(['cost' => $totalCost]);
        // 🦉 Otra consideración es que si se crean muchas recetas a la vez estamis creando y luego haciendo un update, lo que puede ser ineficiente

        return $this->recipe;
    }
    */



   
}