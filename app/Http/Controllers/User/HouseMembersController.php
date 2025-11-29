<?php

namespace App\Http\Controllers;

use App\Models\House_members;
use Illuminate\Http\Request;

class HouseMembersController extends Controller
{
    function getAllmembers($id = null){
        if(!$id){
            $House_memberss = House_members::all();
            return $this->responseJSON($House_memberss);
        }

        $House_members = House_members::find($id);
        return $this->responseJSON($House_members);
    }

    function addOrUpdatemembers(Request $request, $id = "add"){
        if($id == "add"){
            $House_members = new House_members;
        }else{
            $House_members = House_members::find($id);
            if(!$House_members){
                return $this->responseJSON(null, "failure", 400);
            }
        }

        $House_members->house_hold_id = $request["house_hold_id"];
        $House_members->user_id= $request["user_id"];

        if($House_members->save()){
            return $this->responseJSON($House_members);
        }

        return $this->responseJSON(null, "failure", 400);
    }
}
