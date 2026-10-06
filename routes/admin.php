<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('auth:admin')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])
            ->name('dashboard');
        Route::prefix('admin-management')->name('admin-management.')->group(function () {
            Route::get('/', [AdminController::class, 'index'])->name('index');
            Route::get('/create', [AdminController::class, 'create'])->name('create');
            Route::post('/create', [AdminController::class, 'store'])->name('store');
            Route::get('/edit/{deputy}', [AdminController::class, 'edit'])->name('edit');
            Route::post('/edit/{deputy}', [AdminController::class, 'update'])->name('update');
            Route::delete('/delete/{deputy}', [AdminController::class, 'destroy'])->name('destroy');
        });
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
