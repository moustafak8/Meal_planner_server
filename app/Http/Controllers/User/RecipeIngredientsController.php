<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\recipe_ingredients;
use Illuminate\Http\Request;

class RecipeIngredientsController extends Controller
{
    function getAllRecipeIngredients($id = null){
         if(!$id){
              $recipe_ingredients = recipe_ingredients::all();
              return $this->responseJSON($recipe_ingredients);
         }
    
         $recipe_ingredient = recipe_ingredients::find($id);
         return $this->responseJSON($recipe_ingredient);
    }
      function addOrUpdateRecipeIngredient(Request $request, $id = "add"){
            if($id == "add"){
                 $recipe_ingredient = new recipe_ingredients;
            }else{
                 $recipe_ingredient = recipe_ingredients::find($id);
                 if(!$recipe_ingredient){
                  return $this->responseJSON(null, "failure", 400);
                 }
            }
    
            $recipe_ingredient->recipe_id = $request["recipe_id"];
            $recipe_ingredient->unit_id= $request["unit_id"];
            $recipe_ingredient->ingredient_id= $request["ingredient_id"];
            $recipe_ingredient->quantity= $request["quantity"];
            if($recipe_ingredient->save()){
                 return $this->responseJSON($recipe_ingredient);
            }
    
            return $this->responseJSON(null, "failure", 400);
      }
}
