<?php


namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\meal_plans;
use Illuminate\Http\Request;

class MealPlansController extends Controller
{
  function getAllMealPlans($id = null){
      if(!$id){
          $meal_plans = meal_plans::all();
          return $this->responseJSON($meal_plans);
      }

      $meal_plan = meal_plans::find($id);
      return $this->responseJSON($meal_plan);
  }
    function addOrUpdateMealPlan(Request $request, $id = "add"){
        if($id == "add"){
            $meal_plan = new meal_plans;
        }else{
            $meal_plan = meal_plans::find($id);
            if(!$meal_plan){
                return $this->responseJSON(null, "failure", 400);
            }
        }

        $meal_plan->name = $request["name"];
        $meal_plan->household_id= $request["household_id"];
        $meal_plan->created_by= $request["user_id"];
        $meal_plan->start_date= $request["start_date"];

        if($meal_plan->save()){
            return $this->responseJSON($meal_plan);
        }

        return $this->responseJSON(null, "failure", 400);
    }
}
