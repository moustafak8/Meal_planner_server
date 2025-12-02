<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\householdcontroller;
use App\Http\Controllers\User\HouseMembersController;
use App\Http\Controllers\User\IngredientController;
use App\Http\Controllers\User\UnitsController;
use App\Http\Controllers\User\PantryItemsController;
use App\Http\Controllers\User\PantryHistoryController;
use App\Http\Controllers\User\MealPlansController;
use App\Http\Controllers\User\MealPlanEntriesController;
use App\Http\Controllers\User\RecipesController;
use App\Http\Controllers\User\RecipeIngredientsController;
use App\Http\Controllers\User\ShoppingListController;
use App\Http\Controllers\User\ShoppingListItemsController;
use App\Http\Controllers\User\ExpensesController;

//Authenticated Routes
Route::group(["prefix" => "v0.1", "middleware" => "auth:api"], function () {
    Route::group(["prefix" => "user"], function () {
        Route::get('/household/{id?}', [householdcontroller::class, "getAllhouseholds"]);
        Route::post('/add_update_household/{id?}', [householdcontroller::class, "addOrUpdatehousehold"]);
        Route::get('/members/{id?}', [HouseMembersController::class, "getAllmembers"]);
        Route::post('/add_update_members/{id?}', [HouseMembersController::class, "addOrUpdatemembers"]);
        Route::get('/ingredients/{id?}', [IngredientController::class, "getAllIngredients"]);
        Route::post('/add_update_ingredient/{id?}', [IngredientController::class, "addOrUpdateIngredient"]);
        Route::post('/unit/{id?}', [UnitsController::class, "getAllUnits"]);
        Route::post('/add_update_unit/{id?}', [UnitsController::class, "addOrUpdateUnit"]);
        Route::get('/pantry_items/{id?}', [PantryItemsController::class, "getAllPantryItems"]);
        Route::post('/add_update_pantry_item/{id?}', [PantryItemsController::class, "addOrUpdatePantryItem"]);
        Route::post('/consume_pantry_item/{id}', [PantryItemsController::class, "consumePantryItem"]);
        Route::get('/pantry_history/{id?}', [PantryHistoryController::class, "getAllPantryHistory"]);
        Route::post('/add_update_pantry_history/{id?}', [PantryHistoryController::class, "addOrUpdatePantryHistory"]);
        Route::get('/meal_plans/{id?}', [MealPlansController::class, "getAllMealPlans"]);
        Route::post('/add_update_meal_plan/{id?}', [MealPlansController::class, "addOrUpdateMealPlan"]);
        Route::get('/meal_plan_entries/{id?}', [MealPlanEntriesController::class, "getAllMealPlanEntries"]);
        Route::post('/add_update_meal_plan_entry/{id?}', [MealPlanEntriesController::class, "addOrUpdateMealPlanEntry"]);
        Route::get('/recipes/{id?}', [RecipesController::class, "getAllRecipes"]);
        Route::post('/add_update_recipe/{id?}', [RecipesController::class, "addOrUpdateRecipe"]);
        Route::get('/recipe_ingredients/{id?}', [RecipeIngredientsController::class, "getAllRecipeIngredients"]);
        Route::post('/add_update_recipe_ingredient  /{id?}', [RecipeIngredientsController::class, "addOrUpdateRecipeIngredient"]);
        Route::get('/shopping_lists/{id?}', [ShoppingListController::class, "getAllShoppingLists"]);
        Route::post('/add_update_shopping_list/{id?}', [ShoppingListController::class, "addOrUpdateShoppingList"]);
        Route::get('/shopping_list_items/{id?}', [ShoppingListItemsController::class, "getAllShoppingListItems"]);
        Route::post('/add_update_shopping_list_item/{id?}', [ShoppingListItemsController::class, "addOrUpdateShoppingListItem"]);
        Route::get('/expenses/{id?}', [ExpensesController::class, "getAllExpenses"]);
        Route::post('/add_update_expense/{id?}', [ExpensesController::class, "addOrUpdateExpense"]);
    });
});
Route::post('/login', [AuthController::class, "login"]);
Route::post('/register', [AuthController::class, "register"]);
Route::get('/error', [AuthController::class, "displayError"])->name("login");
