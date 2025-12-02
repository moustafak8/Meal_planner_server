<?php
namespace App\Services;
use Illuminate\Http\Request;
use App\Models\recipes;
use App\Models\Ingredient;
use App\Models\recipe_ingredients;
use App\Models\Units;

class Recipe_service
{
    function addrecipe(Request $request)
    {
        $data = $request->all();
            $recipesData = [$data];

        foreach ($recipesData as $recipeData) {

            $recipe = recipes::create([
                'title' => $recipeData['name'],
                'instruction' => $recipeData['instructions'],
                'tags' => $recipeData['tags'],
                'user_id' => $recipeData['user_id']
            ]);

            foreach ($recipeData['ingredients'] as $ingredientData) {
                // Find or create the unit
                $unit = Units::firstOrCreate(['name' => $ingredientData['unit']]);

                // if ingredient does not exist, create it
                $ingredient = Ingredient::firstOrCreate(
                    ['name' => $ingredientData['name']],
                    ['unit_id' => $unit->id]
                );

                // Insert into recipe_ingredients
                recipe_ingredients::create([
                    'recipe_id' => $recipe->id,
                    'ingredient_id' => $ingredient->id,
                    'unit_id' => $unit->id,
                    'quantity' => $ingredientData['quantity'],
                ]);
            }
        }

        return ['status' => 'Recipes added successfully'];
    }
}
