<?php

namespace App\Services\Sales;
use App\Models\Recipe;
use App\Models\SaleLine;

class SalesService
{
    public function __construct()
    {
        // 
        // parent::__construct();

    }

    /*
    * Add a new sale line
    * 
    */
    public function createSaleLine(\DateTime $fecha, Recipe $recipe)
    {
        // creamos una nueva venta
        $sale = SaleLine::create([
            'recipe_id' => $recipe->id,
            'price' => $recipe->sale_price,
            'sale_date' => $fecha
        ]);
        return $sale;
        

    }

    
}


