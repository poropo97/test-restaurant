<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['sale_date'];

    // Relación uno a muchos con las líneas de venta
    public function lines()
    {
        return $this->hasMany(SaleLine::class);
    }
}
