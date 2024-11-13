<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Ingredient;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Ingredient::insert([
            ['name' => 'Aguacate', 'cost' => 2],
            ['name' => 'Cebolla', 'cost' => 1],
            ['name' => 'Tomate', 'cost' => 1],
            ['name' => 'Limón', 'cost' => 0.5],
            ['name' => 'Sal', 'cost' => 0.01],
            ['name' => 'Totopos', 'cost' => 1],
            ['name' => 'Ron', 'cost' => 2],
            ['name' => 'Coca Cola', 'cost' => 0.5],
        ]);

        
    }
}
