<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = [
        'title',
        'description',
        'category',
        'prep_time',
        'servings',
        'image_path',
        'total_calories',
    ];

    public function ingredients()
    {
        return $this->hasMany(Ingredient::class);
    }
}
