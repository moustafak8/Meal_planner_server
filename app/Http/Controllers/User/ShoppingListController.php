<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\shopping_list;
use Illuminate\Http\Request;

class ShoppingListController extends Controller
{
    public function getAllShoppingLists($id = null)
    {
        if (!$id) {
            $shoppingLists = shopping_list::all();
            return $this->responseJSON($shoppingLists);
        }

        $shoppingList = shopping_list::find($id);
        return $this->responseJSON($shoppingList);
    }

    public function addOrUpdateShoppingList(Request $request, $id = "add")
    {
        if ($id == "add") {
            $shoppingList = new shopping_list;
        } else {
            $shoppingList = shopping_list::find($id);
            if (!$shoppingList) {
                return $this->responseJSON(null, "failure", 400);
            }
        }
        $shoppingList->household_id=$request["household_id"];
        $shoppingList->created_by = $request["user_id"];
        $shoppingList->meal_plan_id = $request->has('meal_plan_id') ? $request["meal_plan_id"] : null;
        $shoppingList->name = $request["name"];
        $shoppingList->cost = $request["cost"];
        $shoppingList->status = $request["status"];

        if ($shoppingList->save()) {
            return $this->responseJSON($shoppingList);
        }

        return $this->responseJSON(null, "failure", 400);
    }
    
}
