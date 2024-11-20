<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Escandallo;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'sale_price', 'cost'];

    public function escandallos()
    {
        return $this->hasMany(Escandallo::class, 'recipe_id');
    }

    // Relación muchos a muchos con Ingredient
    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'recipe_ingredients')
                    ->withPivot('quantity');
    }

   
}
