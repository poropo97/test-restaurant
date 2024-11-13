<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\UseCases\AddSale;

class AddSaleCommand extends Command
{
    protected $signature = 'sale:add {sale_date} {lines*}';
    protected $description = 'Agregar una venta y calcular el margen de beneficio y volumen de ventas';

    public function __construct(AddSale $addSale)
    {
        parent::__construct();
        $this->addSale = $addSale;
    }

    public function handle()
    {
        // Obtener los datos de entrada desde los argumentos del comando
        $saleDate = $this->argument('sale_date');
        $linesData = $this->argument('lines');

        // Preparar las líneas de venta en el formato requerido
        $lines = [];
        foreach ($linesData as $line) {
            [$recipeId, $quantity, $price] = array_pad(explode(',', $line), 3, null);
            $lines[] = [
                'recipe_id' => $recipeId,
                'quantity' => $quantity,
                'price' => $price,
            ];
        }

        // Ejecutar el caso de uso para agregar la venta
        $saleAnalysis = $this->addSale->execute([
            'sale_date' => $saleDate,
            'lines' => $lines,
        ]);

        // Mostrar los resultados en consola
        $this->info("Venta agregada para la fecha: $saleDate");
        $this->info("Margen de beneficio de cada escandallo:");
        foreach ($saleAnalysis['margins'] as $recipe => $margin) {
            $this->info("- $recipe: $margin%");
        }
        $this->info("Día con mayor volumen de ventas: {$saleAnalysis['highest_sales_day'][0]}, {$saleAnalysis['highest_sales_day'][1]}");
        $this->info("Día con menor volumen de ventas: {$saleAnalysis['lowest_sales_day'][0]}, {$saleAnalysis['lowest_sales_day'][1]}");
    }
}
