<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\User\householdcontroller;
use App\Models\House_members;

//Authenticated Routes

Route::group(["prefix" => "v0.1", "middleware" => "auth:api"], function(){
    Route::group(["prefix" => "user"], function(){
        Route::get('/household/{id?}', [householdcontroller::class, "getAllhouseholds"]);
        Route::post('/add_update_household/{id?}', [householdcontroller::class, "addOrUpdatehousehold"]);
          Route::get('/members/{id?}', [House_members::class, "getAllmembers"]);
        Route::post('/add_update_household/{id?}', [House_members::class, "addOrUpdatemembers"]);

    });
});


Route::post('/login', [AuthController::class, "login"]);
Route::post('/register', [AuthController::class, "register"]);
Route::get('/error', [AuthController::class, "displayError"])->name("login");



