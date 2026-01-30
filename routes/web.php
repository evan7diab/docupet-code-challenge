<?php

use App\Http\Controllers\PetOwnerRegistrationController;
use App\Http\Controllers\VueAppController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/pet-owner/register', PetOwnerRegistrationController::class)->name('pet-owner.register');

Route::get('/vue-app', VueAppController::class)->name('vue-app');
