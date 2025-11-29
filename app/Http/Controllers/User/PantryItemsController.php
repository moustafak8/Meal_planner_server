<?php


namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\pantry_items;
use Illuminate\Http\Request;

class PantryItemsController extends Controller
{
    function getAllPantryItems($id = null)
    {
        if (!$id) {
            $pantry_items = pantry_items::all();
            return $this->responseJSON($pantry_items);
        }

        $pantry_item = pantry_items::find($id);
        return $this->responseJSON($pantry_item);
    }

    function addOrUpdatePantryItem(Request $request, $id = "add")
    {
        if ($id == "add") {
            $pantry_item = new pantry_items;
        } else {
            $pantry_item = pantry_items::find($id);
            if (!$pantry_item) {
                return $this->responseJSON(null, "failure", 400);
            }
        }
        $pantry_item->household_id = $request["household_id"];
        $pantry_item->added_by= $request["user_id"];
        $pantry_item->unit_id = $request["unit_id"];
        $pantry_item->ingredient_id = $request["ingredient_id"];
        $pantry_item->name = $request["name"];
        $pantry_item->quantity = $request["quantity"];
        $pantry_item->expiration_date = $request["expiration_date"];
        $pantry_item->location = $request["location"];
        if ($pantry_item->save()) {
            return $this->responseJSON($pantry_item);
        }

        return $this->responseJSON(null, "failure", 400);
    }
}
