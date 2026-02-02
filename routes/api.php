<?php

use App\Http\Controllers\Api\BreedController;
use App\Http\Controllers\Api\PetController;
use App\Http\Controllers\Api\TypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api.key')->group(function () {
    Route::get('/types', [TypeController::class, 'index']);
    Route::get('/breeds', [BreedController::class, 'index']);
    Route::post('/pets', [PetController::class, 'store']);
});
