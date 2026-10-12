<?php

use App\Http\Controllers\Alibnhamze\AuthController;
use App\Http\Controllers\Alibnhamze\ParentDashboardController;
use App\Http\Controllers\Alibnhamze\PreRegistrationController;
use App\Http\Controllers\Alibnhamze\StudentDashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login.showLoginForm');
Route::post('/login/send-otp', [AuthController::class, 'sendOtp'])->name('login.sendOtp');
Route::get('/login/otp/{phone}', [AuthController::class, 'showOtpForm'])->name('login.showOtpForm');
Route::post('/login/verify-otp', [AuthController::class, 'verifyOtp'])->name('login.verifyOtp');

// Student pre-registration (public, no account is created).
Route::get('/pre-register', [PreRegistrationController::class, 'create'])->name('pre-register.create');
Route::post('/pre-register', [PreRegistrationController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('pre-register.store');
Route::get('/pre-register/verify', [PreRegistrationController::class, 'showOtpForm'])->name('pre-register.otp.form');
Route::post('/pre-register/verify', [PreRegistrationController::class, 'verifyOtp'])
    ->middleware('throttle:10,1')
    ->name('pre-register.otp.verify');
Route::post('/pre-register/resend', [PreRegistrationController::class, 'resendOtp'])
    ->middleware('throttle:5,1')
    ->name('pre-register.otp.resend');
Route::post('/pre-register/edit-phone', [PreRegistrationController::class, 'editPhone'])->name('pre-register.edit-phone');
Route::get('/pre-register/success', [PreRegistrationController::class, 'success'])->name('pre-register.success');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/ourteam', function () {
    return view('alibnhamze.our.index');
})->name('ourteam');// Dashboards

Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard')->middleware('auth');
Route::get('/parent/dashboard', [ParentDashboardController::class, 'index'])->name('parent.dashboard')->middleware('auth');
