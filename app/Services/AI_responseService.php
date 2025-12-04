<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AI_responseService
{
    public static function suggestRecipes($pantryItems)
    {
        $apiKey = config('services.openai.api_key');
        $model = config('services.openai.model');

        if (!$apiKey) {
            Log::error('OpenAI API key not configured');
            return json_encode(["error" => "OpenAI API key not configured"]);
        }

        $system = "You are a helpful cooking assistant. Based on the pantry items provided, suggest 3 practical recipes that can be made with available ingredient(s). Return ONLY valid JSON in this format:
        {
            \"recipes\": [
                {
                    \"name\": \"Recipe Name\",
                    \"ingredients\": [
                        {\"name\": \"ingredient1\", \"quantity\": \"1\", \"unit\": \"kg\"},
                        {\"name\": \"ingredient2\", \"quantity\": \"2\", \"unit\": \"cups\"},
                        ...
                    ],
                    \"instructions\": \"Brief cooking instructions\",
                    \"tags\": \"tags of the recipe\",
                }
            ]
        }
        For each ingredient, provide a realistic quantity and unit based on the recipe. Use appropriate units like kg, g, cups, tbsp, etc. Keep suggestions realistic based on the ingredient(s) provided. If limited ingredients, suggest simple recipes. Focus on practical, everyday meals.";

        $itemsList = "";
        foreach ($pantryItems as $item) {
            $itemsList .= "- {$item['name']} ({$item['quantity']} {$item['unit']})\n";
        }

        $userContent = "Here are my available pantry items:\n\n" . $itemsList . "\n\nSuggest 3-5 recipes I can make with these ingredients.";

        $payload = [
            "model" => $model,
            "messages" => [
                ["role" => "system", "content" => $system],
                ["role" => "user", "content" => $userContent]
            ],
            "max_tokens" => 1500,
            "temperature" => 0.3
        ];

        try {
            $response = Http::withHeaders([
                "Authorization" => "Bearer " . $apiKey,
                "Content-Type" => "application/json",
            ])->post("https://api.openai.com/v1/chat/completions", $payload);

            if (!$response->successful()) {
                $errorMsg = $response->json()['error']['message'] ?? "HTTP {$response->status()} error";
                Log::error('OpenAI API error: ' . $errorMsg);
                return json_encode(["error" => $errorMsg]);
            }

            $result = $response->json();
            $content = $result['choices'][0]['message']['content'] ?? "";

            if (empty($content)) {
                Log::error('Empty AI response from OpenAI');
                return json_encode(["error" => "Empty AI response"]);
            }

            $decoded = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Invalid JSON from AI: ' . json_last_error_msg());
                return json_encode(["error" => "Invalid JSON from AI: " . json_last_error_msg()]);
            }

            return json_encode($decoded);
        } catch (\Exception $e) {
            Log::error('Exception in suggestRecipes: ' . $e->getMessage());
            return json_encode(["error" => "Internal server error"]);
        }
    }

    public static function generateShoppingList($mealPlanEntries, $pantryItems)
    {
        $apiKey = config('services.openai.api_key');
        $model = config('services.openai.model');

        if (!$apiKey) {
            Log::error('OpenAI API key not configured');
            return json_encode(["error" => "OpenAI API key not configured"]);
        }

        $system = "You are a helpful meal planning assistant. Based on the meal plan entries and pantry items provided, generate a shopping list of missing ingredients needed for the meal plans. Return ONLY valid JSON in this format:
        {
            \"shopping_list\": [
                {
                    \"name\": \"Ingredient Name\",
                    \"quantity\": \"required quantity\",
                    \"unit\": \"unit\"
                },
                ...
            ]
        }
        Analyze the recipes in the meal plan entries, their ingredients, and compare with pantry items. Only include ingredients that are missing or insufficient in quantity. Aggregate quantities if the same ingredient is needed for multiple recipes. Use appropriate units.";

        $mealPlanText = "Meal Plan Entries:\n";
        foreach ($mealPlanEntries as $entry) {
            $mealPlanText .= "- Recipe ID: {$entry['recipe_id']}, Date: {$entry['date']}, Meal Type: {$entry['meal_type']}, Description: {$entry['description']}\n";
        }

        $pantryText = "Pantry Items:\n";
        foreach ($pantryItems as $item) {
            $pantryText .= "- {$item['name']}: {$item['quantity']} {$item['unit']}\n";
        }

        $userContent = $mealPlanText . "\n" . $pantryText . "\n\nGenerate the shopping list of missing ingredients.";

        $payload = [
            "model" => $model,
            "messages" => [
                ["role" => "system", "content" => $system],
                ["role" => "user", "content" => $userContent]
            ],
            "max_tokens" => 1500,
            "temperature" => 0.3
        ];

        try {
            $response = Http::withHeaders([
                "Authorization" => "Bearer " . $apiKey,
                "Content-Type" => "application/json",
            ])->post("https://api.openai.com/v1/chat/completions", $payload);

            if (!$response->successful()) {
                $errorMsg = $response->json()['error']['message'] ?? "HTTP {$response->status()} error";
                Log::error('OpenAI API error: ' . $errorMsg);
                return json_encode(["error" => $errorMsg]);
            }

            $result = $response->json();
            $content = $result['choices'][0]['message']['content'] ?? "";

            if (empty($content)) {
                Log::error('Empty AI response from OpenAI');
                return json_encode(["error" => "Empty AI response"]);
            }

            $decoded = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Invalid JSON from AI: ' . json_last_error_msg());
                return json_encode(["error" => "Invalid JSON from AI: " . json_last_error_msg()]);
            }

            return json_encode($decoded);
        } catch (\Exception $e) {
            Log::error('Exception in generateShoppingList: ' . $e->getMessage());
            return json_encode(["error" => "Internal server error"]);
        }
    }
}
