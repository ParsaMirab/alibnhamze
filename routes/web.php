<?php

use App\Http\Controllers\Alibnhamze\AuthController;
use App\Http\Controllers\Alibnhamze\ParentDashboardController;
use App\Http\Controllers\Alibnhamze\StudentDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.showLoginForm');
Route::post('/login/send-otp', [AuthController::class, 'sendOtp'])->name('login.sendOtp');
Route::get('/login/otp/{phone}', [AuthController::class, 'showOtpForm'])->name('login.showOtpForm');
Route::post('/login/verify-otp', [AuthController::class, 'verifyOtp'])->name('login.verifyOtp');
Route::get('/pre-register', [AuthController::class, 'preRegister'])->name('pre-register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboards
Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard')->middleware('auth');
Route::get('/parent/dashboard', [ParentDashboardController::class, 'index'])->name('parent.dashboard')->middleware('auth');
