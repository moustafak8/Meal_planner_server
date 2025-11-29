<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\householdcontroller;
use App\Http\Controllers\User\HouseMembersController;
use App\Http\Controllers\User\IngredientController;
use App\Http\Controllers\User\UnitsController;  

//Authenticated Routes
Route::group(["prefix" => "v0.1", "middleware" => "auth:api"], function () {
    Route::group(["prefix" => "user"], function () {
        Route::get('/household/{id?}', [householdcontroller::class, "getAllhouseholds"]);
        Route::post('/add_update_household/{id?}', [householdcontroller::class, "addOrUpdatehousehold"]);
        Route::get('/members/{id?}', [HouseMembersController::class, "getAllmembers"]);
        Route::post('/add_update_household/{id?}', [HouseMembersController::class, "addOrUpdatemembers"]);
        Route::get('/ingredients/{id?}', [IngredientController::class, "getAllIngredients"]);
        Route::post('/add_update_ingredient/{id?}', [IngredientController::class, "addOrUpdateIngredient"]);
        Route::post('/unit/{id?}', [UnitsController::class, "getAllUnits"]);
        Route::post('/add_update_unit/{id?}', [UnitsController::class, "addOrUpdateUnit"]);
    });
});


Route::post('/login', [AuthController::class, "login"]);
Route::post('/register', [AuthController::class, "register"]);
Route::get('/error', [AuthController::class, "displayError"])->name("login");
