<?php


namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\meal_plan_entries;
use Illuminate\Http\Request;

class MealPlanEntriesController extends Controller
{
   function getAllMealPlanEntries($id = null){
       if(!$id){
           $meal_plan_entries = meal_plan_entries::all();
           return $this->responseJSON($meal_plan_entries);
       }

       $meal_plan_entry = meal_plan_entries::find($id);
       return $this->responseJSON($meal_plan_entry);
   }
     function addOrUpdateMealPlanEntry(Request $request, $id = "add"){
         if($id == "add"){
             $meal_plan_entry = new meal_plan_entries;
         }else{
             $meal_plan_entry = meal_plan_entries::find($id);
             if(!$meal_plan_entry){
                 return $this->responseJSON(null, "failure", 400);
             }
         }

         $meal_plan_entry->meal_plan_id = $request["meal_plan_id"];
         $meal_plan_entry->recipe_id= $request["recipe_id"];
         $meal_plan_entry->date= $request["date"];
         $meal_plan_entry->meal_type= $request["meal_type"];
         $meal_plan_entry->description= $request["description"];

         if($meal_plan_entry->save()){
             return $this->responseJSON($meal_plan_entry);
         }

         return $this->responseJSON(null, "failure", 400);
     }
}
