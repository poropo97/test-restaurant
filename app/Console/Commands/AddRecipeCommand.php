<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\RecipeService;
use App\Models\Recipe;
use App\Models\Ingredient;

class AddRecipeCommand extends Command
{
    protected $signature = 'recipe:add {name} {sale_price}';
    protected $description = 'Agregar una nueva receta con sus ingredientes y calcular análisis de costes';

    protected $recipeService;

    public function __construct(RecipeService $recipeService)
    {
        parent::__construct();
        $this->recipeService = $recipeService;
    }

    public function handle()
    {
        // Obtener los datos de entrada desde los argumentos del comando
        $name = $this->argument('name');
        $salePrice = $this->argument('sale_price');

        // Preparar los ingredientes en el formato requerido
        $ingredients = [];
        while (true) {
            $ingredientType = $this->confirm('¿El ingrediente es una receta existente?');

            if ($ingredientType) {
                $recipes = Recipe::all()->pluck('name', 'id')->toArray();
                $recipeId = $this->choice('Seleccione la receta', $recipes);
                $quantity = $this->ask('Ingrese la cantidad de la receta');
                $ingredients[] = ['id' => $recipeId, 'quantity' => $quantity, 'type' => 'recipe'];
            } else {
                $ingredientsList = Ingredient::all()->pluck('name', 'id')->toArray();
                $ingredientId = $this->choice('Seleccione el ingrediente', $ingredientsList);
                $quantity = $this->ask('Ingrese la cantidad del ingrediente');
                $ingredients[] = ['id' => $ingredientId, 'quantity' => $quantity, 'type' => 'ingredient'];
            }

            if (!$this->confirm('¿Desea agregar otro ingrediente?')) {
                break;
            }
        }

        // Ejecutar el caso de uso para agregar la receta
        $recipe = $this->recipeService->createRecipe($name, $salePrice, $ingredients);

        $this->info('Receta creada exitosamente: ' . $recipe->name);
    }
}