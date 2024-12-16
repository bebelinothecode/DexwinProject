<?php

use App\Http\Controllers\TodoController;
use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post("/v1/createtodo", [TodoController::class, 'createtodo']);
Route::post("/v1/updatetodo/{id}", [TodoController::class, 'updatetodo']);
Route::get("/v1/todos", [TodoController::class, 'todos']);
Route::delete("v1/delete/{id}", [TodoController::class, 'deletetodo']);
Route::get('/v1/todo/{id}', [TodoController::class, 'todo']);
Route::get('/v1/filter/todo', [TodoController::class, 'filterbystatus']);
Route::get('/v1/search/todo', [TodoController::class, 'index']);
