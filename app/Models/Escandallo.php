<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Escandallo extends Model
{
    //
    protected $table = 'recipe_ingredients';

    protected $fillable = ['recipe_id', 'ingredient_id', 'quantity'];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }

    // en hexagonal no debería estar aquí. Debería estar en un servicio o en una clase de dominio
    public function getCost()
    {
        return $this->ingredient->cost * $this->quantity;
    }
}
