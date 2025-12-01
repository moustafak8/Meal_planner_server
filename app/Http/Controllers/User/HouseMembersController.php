<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\House_members;
use App\Models\household;
use Illuminate\Http\Request;

class HouseMembersController extends Controller
{
    function getAllmembers($id = null)
    {
        if (!$id) {
            $House_memberss = House_members::all();
            return $this->responseJSON($House_memberss);
        }

        $House_members = House_members::find($id);
        return $this->responseJSON($House_members);
    }

    function addOrUpdatemembers(Request $request, $id = "add")
    {
        if ($id == "add") {
            // Find household by invite code
            $household = household::where('invite_code', $request["invitecode"])->first();
            if (!$household) {
                return $this->responseJSON(null, "Household not found", 404);
            }

            // Check if user is already a member
            $existingMember = House_members::where('household_id', $household->id)
                ->where('user_id', $request["userid"])
                ->first();
            if ($existingMember) {
                return $this->responseJSON(null, "User is already a member of this household", 400);
            }

            $House_members = new House_members;
            $House_members->household_id = $household->id;
            $House_members->user_id = $request["userid"];
        }
        // Find household by user_id 
        else if ($id == "id") {
            $House_members = House_members::where('user_id', $request["user_id"])->first();
            if (!$House_members) {
                return $this->responseJSON(null, "Member not found", 404);
            }
            return $this->responseJSON($House_members);
        } {
            $House_members = House_members::find($id);
            if (!$House_members) {
                return $this->responseJSON(null, "failure", 400);
            }
            $House_members->household_id = $request["household_id"];
            $House_members->user_id = $request["user_id"];
        }

        if ($House_members->save()) {
            return $this->responseJSON($House_members);
        }

        return $this->responseJSON(null, "failure", 400);
    }
}