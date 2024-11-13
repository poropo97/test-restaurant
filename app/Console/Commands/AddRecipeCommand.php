<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\UseCases\AddRecipe;

class AddRecipeCommand extends Command
{
    protected $signature = 'recipe:add {name} {sale_price} {ingredients*}';
    protected $description = 'Agregar una nueva receta con sus ingredientes y calcular análisis de costes';

    public function __construct(AddRecipe $addRecipe)
    {
        parent::__construct();
        $this->addRecipe = $addRecipe;
    }

    public function handle()
    {
        // Obtener los datos de entrada desde los argumentos del comando
        $name = $this->argument('name');
        $salePrice = $this->argument('sale_price');
        $ingredientsData = $this->argument('ingredients');

        // Preparar los ingredientes en el formato requerido
        $ingredients = [];
        foreach ($ingredientsData as $ingredient) {
            [$id, $quantity] = explode(',', $ingredient);
            $ingredients[] = ['id' => $id, 'quantity' => $quantity];
        }

        // Ejecutar el caso de uso para agregar la receta
        $recipe = $this->addRecipe->execute([
            'name' => $name,
            'sale_price' => $salePrice,
            'ingredients' => $ingredients,
        ]);

        // Obtener el análisis de costes
        $costAnalysis = $this->addRecipe->getCostAnalysis();

        // Mostrar los resultados en consola
        $this->info("Receta agregada: {$recipe->name}");
        $this->info("Análisis de costes:");
        $this->info("Receta con mayor coste: {$costAnalysis['highest_cost'][0]}, {$costAnalysis['highest_cost'][1]}");
        $this->info("Receta con menor coste: {$costAnalysis['lowest_cost'][0]}, {$costAnalysis['lowest_cost'][1]}");
        $this->info("Receta con mayor margen de beneficio: {$costAnalysis['highest_margin'][0]}, {$costAnalysis['highest_margin'][1]}%");
        $this->info("Receta con menor margen de beneficio: {$costAnalysis['lowest_margin'][0]}, {$costAnalysis['lowest_margin'][1]}%");
    }
}
