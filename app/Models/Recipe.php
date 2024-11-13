<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'sale_price', 'cost'];

    // Relación muchos a muchos con Ingredient
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredients')
                    ->withPivot('quantity');
    }

    // Relación muchos a muchos con otras recetas (sub-recetas)
    public function subRecipes()
    {
        return $this->belongsToMany(Recipe::class, 'recipe_ingredients', 'recipe_id', 'ingredient_id')
                    ->withPivot('quantity');
    }

    // Método para calcular el margen de beneficio
    public function getMargin()
    {
        if ($this->sale_price == 0) {
            return 0; // O puedes devolver null u otro valor que indique que el margen no es aplicable.
        }
        return ($this->sale_price - $this->cost) / $this->sale_price * 100;    }
}
