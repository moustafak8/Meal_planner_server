<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class recipe_ingredients extends Model
{
    protected $fillable = ['recipe_id', 'ingredient_id', 'unit_id', 'quantity'];
    protected $table = 'recipes-ingredients';
}
