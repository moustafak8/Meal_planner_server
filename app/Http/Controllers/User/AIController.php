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

    public function generateShoppingList(Request $request)
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

            // Get meal plan entries for the household
            $mealPlanEntries = \App\Models\meal_plan_entries::whereHas('mealPlan', function ($query) use ($householdId) {
                $query->where('household_id', $householdId);
            })->with('mealPlan')->get();

            if ($mealPlanEntries->isEmpty()) {
                return $this->responseJSON(null, "No meal plan entries found", 404);
            }

            // Get pantry items for the household
            $pantryItems = pantry_items::where('household_id', $householdId)
                ->with('unit')
                ->get();

            $formattedPantry = $pantryItems->map(function ($item) {
                return [
                    'name' => $item->name,
                    'quantity' => $item->quantity,
                    'unit' => $item->unit ? $item->unit->name : 'pieces'
                ];
            })->toArray();

            $formattedEntries = $mealPlanEntries->map(function ($entry) {
                return [
                    'recipe_id' => $entry->recipe_id,
                    'date' => $entry->date,
                    'meal_type' => $entry->meal_type,
                    'description' => $entry->description
                ];
            })->toArray();

            $aiResponse = AI_responseService::generateShoppingList($formattedEntries, $formattedPantry);
            $decodedResponse = json_decode($aiResponse, true);
            if (isset($decodedResponse['error'])) {
                return $this->responseJSON(null, $decodedResponse['error'], 500);
            }

            return $this->responseJSON($decodedResponse, "Shopping list generated successfully");
        } catch (\Exception $e) {
            Log::error('Exception in AIController@generateShoppingList: ' . $e->getMessage());
            return $this->responseJSON(null, "Internal server error", 500);
        }
    }
}
