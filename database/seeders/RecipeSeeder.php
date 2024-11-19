<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Recipe;



class RecipeSeeder extends Seeder
{
    public function run()
    {
        // $guacamole = Recipe::create(['name' => 'Guacamole', 'sale_price' => 0, 'cost' => 0]);
        // $guacamole->ingredients()->attach([
        //     1 => ['quantity' => 2], // Aguacate
        //     2 => ['quantity' => 1], // Cebolla
        //     3 => ['quantity' => 1], // Tomate
        //     4 => ['quantity' => 0.5], // Limón
        //     5 => ['quantity' => 0.01], // Sal
        // ]);

        // $nachos = Recipe::create(['name' => 'Nachos con guacamole', 'sale_price' => 10, 'cost' => 0]);
        // $nachos->ingredients()->attach([
        //     6 => ['quantity' => 1], // Totopos
        //     $guacamole->id => ['quantity' => 4.51], // Guacamole como sub-receta
        // ]);

        // $ronCola = Recipe::create(['name' => 'Ron Cola', 'sale_price' => 8, 'cost' => 0]);
        // $ronCola->ingredients()->attach([
        //     7 => ['quantity' => 2], // Ron
        //     8 => ['quantity' => 0.5], // Coca Cola
        // ]);
    }
}
