<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = [
        'recipe_id',
        'name',
        'quantity',
        'unit',
        'calories_per_100g',
    ];

    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
