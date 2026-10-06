<?php

use App\Http\Controllers\Alibnhamze\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::get('/pre-register', [AuthController::class, 'preRegister'])->name('pre-register');
