<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class meal_plan_entries extends Model
{
    public function mealPlan()
    {
        return $this->belongsTo(meal_plans::class, 'meal_plan_id');
    }
}
