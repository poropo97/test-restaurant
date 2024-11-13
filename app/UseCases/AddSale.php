<?php
namespace App\UseCases;

use App\Models\Sale;
use App\Models\Recipe;

class AddSale
{
    public function execute($data)
    {
        $sale = Sale::create(['sale_date' => $data['sale_date']]);

        foreach ($data['lines'] as $line) {
            $recipe = Recipe::find($line['recipe_id']);

            if (!$recipe) {
                throw new \Exception("La receta con ID {$line['recipe_id']} no existe.");
            }

            $price = $line['price'] ?? $recipe->sale_price;

            $sale->lines()->create([
                'recipe_id' => $line['recipe_id'],
                'quantity' => $line['quantity'],
                'price' => $price,
            ]);
        }

        return $this->analyzeSales();
    }

    public function analyzeSales()
    {
        // Calcular el margen de beneficio para cada receta en la venta
        $recipes = Recipe::all();
        $margins = $recipes->mapWithKeys(function($recipe) {
            return [$recipe->name => $recipe->getMargin()];
        });

        // Obtener el día con mayor y menor volumen de ventas
        $sales = Sale::with('lines')->get()->groupBy('sale_date');
        
        $salesVolume = $sales->map(function ($salesByDate) {
            return $salesByDate->sum(function($sale) {
                return $sale->lines->sum(fn($line) => $line->quantity * $line->price);
            });
        });
        
        $maxSalesDay = $salesVolume->sortDesc()->first();
        $minSalesDay = $salesVolume->sort()->first();

        // Devolver los resultados con formato adecuado
        return [
            'margins' => $margins,
            'highest_sales_day' => [$salesVolume->sortDesc()->keys()->first(), $maxSalesDay],
            'lowest_sales_day' => [$salesVolume->sort()->keys()->first(), $minSalesDay],
        ];
    }
}
