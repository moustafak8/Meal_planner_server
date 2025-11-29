<?php

namespace App\Http\Controllers\User;
use App\Http\Controllers\Controller;
use App\Models\Units;
use Illuminate\Http\Request;

class UnitsController extends Controller
{
    function getAllUnits($id = null){
        if(!$id){
            $units = Units::all();
            return $this->responseJSON($units);
        }

        $unit = Units::find($id);
        return $this->responseJSON($unit);
    }

    function addOrUpdateUnit(Request $request, $id = "add"){
        if($id == "add"){
            $unit = new Units;
        }else{
            $unit = Units::find($id);
            if(!$unit){
                return $this->responseJSON(null, "failure", 400);
            }
        }

        $unit->name = $request["name"];
        if($unit->save()){
            return $this->responseJSON($unit);
        }

        return $this->responseJSON(null, "failure", 400);
    }
   
}
