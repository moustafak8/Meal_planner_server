<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

use App\Models\expenses;
use Illuminate\Http\Request;

class ExpensesController extends Controller
{
    function getAllExpenses($id = null){
        if(!$id){
            $expenses = expenses::all();
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
            $expense->title= $request["title"];
            $expense->amount_spent= $request["amount_spent"];
            $expense->Currency= $request["Currency"];
            $expense->category= $request["category"];
            $expense->notes= $request["notes"];
            $expense->expense_date= $request["date"];
    
            if($expense->save()){
                return $this->responseJSON($expense);
            }
    
            return $this->responseJSON(null, "failure", 400);
        }
}
