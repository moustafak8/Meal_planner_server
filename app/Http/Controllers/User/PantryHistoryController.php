<?php
namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\pantry_history;
use Illuminate\Http\Request;

class PantryHistoryController extends Controller {
    function getAllPantryHistory($id = null){
        if(!$id){
            $pantry_histories = pantry_history::all();
            return $this->responseJSON($pantry_histories);
        }

        $pantry_history = pantry_history::find($id);
        return $this->responseJSON($pantry_history);
    }
    function addOrUpdatePantryHistory(Request $request, $id = "add"){
        if($id == "add"){
            $pantry_history = new pantry_history;
        }else{
            $pantry_history = pantry_history::find($id);
            if(!$pantry_history){
                return $this->responseJSON(null, "failure", 400);
            }
        }

        $pantry_history->pantry_item_id = $request["pantry_item_id"];
        $pantry_history->changed_by = $request["user_id"];
        $pantry_history->unit=$request["unit_id"];
        $pantry_history->change_type= $request["change_type"];
        $pantry_history->quantity_changed = $request["quantity_changed"];
        $pantry_history->reason= $request["reason"];
        if($pantry_history->save()){
            return $this->responseJSON($pantry_history);
        }

        return $this->responseJSON(null, "failure", 400);
    }
}
