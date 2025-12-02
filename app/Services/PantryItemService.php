<?php

namespace App\Services;

use App\Models\pantry_items;
use App\Models\pantry_history;
use Illuminate\Support\Facades\DB;

class PantryItemService
{
    public function consumePantryItem($pantryItemId, $quantityConsumed, $userId, $reason = null)
    {
        DB::beginTransaction();

        try {
           
            $pantryItem = pantry_items::find($pantryItemId);

            if (!$pantryItem) {
                return ['success' => false, 'message' => 'Pantry item not found', 'status' => 404];
            }
            if ($pantryItem->quantity < $quantityConsumed) {
                return ['success' => false, 'message' => 'Insufficient quantity available', 'status' => 400];
            }
            $pantryItem->quantity -= $quantityConsumed;
            $pantryItem->save();

           
            $history = new pantry_history();
            $history->pantry_item_id = $pantryItemId;
            $history->changed_by = $userId;
            $history->unit_id = $pantryItem->unit_id;
            $history->quantity_changed = -$quantityConsumed; // Negative for consumption
            $history->change_type = 'consumed';
            $history->reason = $reason;
            $history->save();
            DB::commit();

            return [
                'success' => true,
                'message' => 'Pantry item consumed successfully',
                'data' => $pantryItem,
                'status' => 200
            ];

        } catch (\Exception $e) {
            DB::rollback();
            return ['success' => false, 'message' => 'An error occurred while consuming the pantry item', 'status' => 500];
        }
    }
}
