<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleLine extends Model
{
    use HasFactory;

    public $timestamps = false; // Desactiva el manejo de timestamps

    protected $fillable = ['sale_id', 'recipe_id', 'quantity', 'price'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
