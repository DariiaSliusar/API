<?php

use App\Http\Controllers\Api\V1\TicketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// http://localhost:8800/api/
// universal resource locator - url
// tickets
// users

Route::middleware('auth:sanctum')->apiResource('tickets', TicketController::class);


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
