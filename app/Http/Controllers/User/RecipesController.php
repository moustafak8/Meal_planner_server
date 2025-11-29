<?php


namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\recipes;
use Illuminate\Http\Request;

class RecipesController extends Controller
{
   function getAllRecipes($id = null){
       if(!$id){
           $recipes = recipes::all();
           return $this->responseJSON($recipes);
       }

       $recipe = recipes::find($id);
       return $this->responseJSON($recipe);
   }
     function addOrUpdateRecipe(Request $request, $id = "add"){
         if($id == "add"){
             $recipe = new recipes;
         }else{
             $recipe = recipes::find($id);
             if(!$recipe){
                 return $this->responseJSON(null, "failure", 400);
             }
         }

         $recipe->title = $request["title"];
         $recipe->tags= $request["tags"];
         $recipe->instructions= $request["instructions"];
        $recipe->created_by= $request["user_id"];
         if($recipe->save()){
             return $this->responseJSON($recipe);
         }

         return $this->responseJSON(null, "failure", 400);
     }
}
