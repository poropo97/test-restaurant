<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Recipe\RecipeService;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Escandallo;

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
                // Mostrar listado de recetas disponibles
                $recipes = Recipe::all(['id', 'name']);
                $this->info('Lista de recetas disponibles:');
                foreach ($recipes as $recipe) {
                    $this->line("ID: {$recipe->id} - Nombre: {$recipe->name}");
                }

                // Solicitar ID de la receta al usuario
                $recipeId = $this->ask('Ingrese el ID de la receta que desea seleccionar');
                
                $quantity = $this->ask('Ingrese la cantidad de la receta');
                $ingredients[] = new Escandallo([
                    'quantity' => $quantity,
                    'recipe_id' => $recipeId,

                ]);
            } else {
                // Mostrar listado de ingredientes disponibles
                $ingredientsList = Ingredient::all(['id', 'name']);
                $this->info('Lista de ingredientes disponibles:');
                foreach ($ingredientsList as $ingredient) {
                    $this->line("ID: {$ingredient->id} - Nombre: {$ingredient->name}");
                }

                // Solicitar ID del ingrediente al usuario
                $ingredientId = $this->ask('Ingrese el ID del ingrediente que desea seleccionar');

                

                $quantity = $this->ask('Ingrese la cantidad del ingrediente');
                $ingredients[] = new Escandallo([
                    'quantity' => $quantity,
                    'ingredient_id' => $ingredientId,
                ]);
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
