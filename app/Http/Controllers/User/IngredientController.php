<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class IngredientController extends Controller
{
    function getAllIngredients($id = null){
        if(!$id){
            $ingredients = Ingredient::all();
            return $this->responseJSON($ingredients);
        }

        $ingredient = Ingredient::find($id);
        return $this->responseJSON($ingredient);
    }

    function addOrUpdateIngredient(Request $request, $id = "add"){
        if($id == "add"){
            $ingredient = new Ingredient;
        }else{
            $ingredient = Ingredient::find($id);
            if(!$ingredient){
                return $this->responseJSON(null, "failure", 400);
            }
        }

        $ingredient->name = $request["name"];
        $ingredient->unit_id= $request["unit_id"];

        if($ingredient->save()){
            return $this->responseJSON($ingredient);
        }

        return $this->responseJSON(null, "failure", 400);
    }
}
