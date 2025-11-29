<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\household;
use Illuminate\Http\Request;

class householdcontroller extends Controller
{
    function getAllhouseholds($id = null){
        if(!$id){
            $households = household::all();
            return $this->responseJSON($households);
        }

        $household = household::find($id);
        return $this->responseJSON($household);
    }

    function addOrUpdatehousehold(Request $request, $id = "add"){
        if($id == "add"){
            $household = new household;
        }else{
            $household = household::find($id);
            if(!$household){
                return $this->responseJSON(null, "failure", 400);
            }
        }

        $household->name = $request["name"];
        $household->invite_code= $request["invite_code"];

        if($household->save()){
            return $this->responseJSON($household);
        }

        return $this->responseJSON(null, "failure", 400);
    }

}

