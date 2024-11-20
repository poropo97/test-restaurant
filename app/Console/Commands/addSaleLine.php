<?php

namespace App\Console\Commands;
use App\Services\Recipe\RecipeService;
use Illuminate\Console\Command;
use App\Services\Sales\SalesService;
use App\Models\Recipe;
use DateTime;

class AddSaleLine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sale';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Agregar líneas de venta a una venta existente';

    protected $saleService;

    public function __construct(SalesService $saleService)
    {
        parent::__construct();
        $this->saleService = $saleService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Mostrar todas las recetas y sus IDs
        $recipes = Recipe::all(['id', 'name']);
        $this->info('Lista de recetas disponibles:');
        foreach ($recipes as $recipe) {
            $this->line("ID: {$recipe->id} - Nombre: {$recipe->name}");
        }

        // Solicitar la fecha y los IDs de las recetas
        $input = $this->ask('Ingrese la fecha y los IDs de las recetas en el formato "YYYY-MM-DD, id1, id2, ..."');
        $parts = explode(',', $input);
        $dateString = trim($parts[0]);
        $recipeIds = array_map('trim', array_slice($parts, 1));

        // Convertir la fecha a un objeto DateTime
        $date = new DateTime($dateString);

        // Crear las líneas de venta
        foreach ($recipeIds as $recipeId) {
            if($recipe = Recipe::find($recipeId)){
                $this->saleService->createSaleLine($date, $recipe);
                // imprimimos por consola el beneficio de cada escandollo
                $recipe->escandallos->each(function($escandollo){
                    $this->info("Beneficio de la receta: " . $escandollo->recipe->name . " es: " . ($escandollo->recipe->sale_price - $escandollo->ingredient->cost));
                });
                
            }
            else
                $this->error("La receta con ID $recipeId no existe.");
            
        }

        $this->info('Líneas de venta creadas exitosamente.');
    }
}