<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\expenses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpensesController extends Controller
{
    function getAllExpenses($id = null){
        if(!$id){
            $expenses = expenses::where('created_by', Auth::user()->id)->get();
            return $this->responseJSON($expenses);
        }
    
        $expense = expenses::find($id);
        return $this->responseJSON($expense);
    }
        function addOrUpdateExpense(Request $request, $id = "add"){
            if($id == "add"){
                $expense = new expenses;
            }else{
                $expense = expenses::find($id);
                if(!$expense){
                    return $this->responseJSON(null, "failure", 400);
                }
            }
    
            $expense->household_id = $request["household_id"];
            $expense->created_by= $request["user_id"];
            $expense->amount_spent= $request["amount_spent"];
            $expense->Currency= $request["Currency"];
            $expense->category= $request["category"];
            $expense->notes= $request["notes"];
            if($expense->save()){
                return $this->responseJSON($expense);
            }
    
            return $this->responseJSON(null, "failure", 400);
        }
}
