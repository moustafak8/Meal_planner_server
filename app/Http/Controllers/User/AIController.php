<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\House_members;
use App\Models\pantry_items;
use App\Models\Units;
use App\Models\Ingredient;
use App\Services\AI_responseService;
use App\Services\Recipe_service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AIController extends Controller
{
    public function suggestion(Request $request, $id = null)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return $this->responseJSON(null, "Unauthorized", 401);
            }
            $houseMember = House_members::where('user_id', $user->id)->first();
            if (!$houseMember) {
                return $this->responseJSON(null, "User not part of any household", 400);
            }

            $householdId = $houseMember->household_id;
            $pantryItems = [];
            if ($id) {
                // Specific pantry item ID provided
                $pantryItems = pantry_items::where('household_id', $householdId)
                    ->where('id', $id)
                    ->with('unit')
                    ->get();
            } else {
                // Get all pantry items for the household
                $pantryItems = pantry_items::where('household_id', $householdId)
                    ->with('unit')
                    ->get();
            }

            if ($pantryItems->isEmpty()) {
                return $this->responseJSON(null, "No pantry items found", 404);
            }
            $formattedItems = $pantryItems->map(function ($item) {
                return [
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                    'unit' => $item->unit ? $item->unit->name : 'pieces'
                ];
            })->toArray();
            $aiResponse = AI_responseService::suggestRecipes($formattedItems);
            $decodedResponse = json_decode($aiResponse, true);
            if (isset($decodedResponse['error'])) {
                return $this->responseJSON(null, $decodedResponse['error'], 500);
            }

            return $this->responseJSON($decodedResponse, "Recipe suggestions generated successfully");
        } catch (\Exception $e) {
            Log::error('Exception in AIController@suggestion: ' . $e->getMessage());
            return $this->responseJSON(null, "Internal server error", 500);
        }
    }
}
