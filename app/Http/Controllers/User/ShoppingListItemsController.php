<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\shopping_list_items;
use Illuminate\Http\Request;

class ShoppingListItemsController extends Controller
{
  function getAllShoppingListItems($id = null){
      if(!$id){
          $shopping_list_items = shopping_list_items::all();
          return $this->responseJSON($shopping_list_items);
      }

      $shopping_list_item = shopping_list_items::find($id);
      return $this->responseJSON($shopping_list_item);
  }
    function addOrUpdateShoppingListItem(Request $request, $id = "add"){
        if($id == "add"){
            $shopping_list_item = new shopping_list_items;
        }else{
            $shopping_list_item = shopping_list_items::find($id);
            if(!$shopping_list_item){
                return $this->responseJSON(null, "failure", 400);
            }
        }

        $shopping_list_item->shopping_list_id = $request["shopping_list_id"];
        $shopping_list_item->ingredient_id= $request["ingredient_id"];
        $shopping_list_item->unit_id= $request["unit_id"];
        $shopping_list_item->name= $request["name"];
        $shopping_list_item->required_amount= $request["required_amount"];
        $shopping_list_item->isbought= $request["isbought"];

        if($shopping_list_item->save()){
            return $this->responseJSON($shopping_list_item);
        }

        return $this->responseJSON(null, "failure", 400);
    }
   
}
