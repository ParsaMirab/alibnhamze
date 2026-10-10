<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ClassStudentController;
use App\Http\Controllers\Admin\ClassTeacherController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SchoolClassController;
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

        // Class Management Routes
        Route::prefix('classes')->name('classes.')->group(function () {
            Route::get('/', [SchoolClassController::class, 'index'])->name('index');
            Route::get('/create', [SchoolClassController::class, 'create'])->name('create');
            Route::post('/', [SchoolClassController::class, 'store'])->name('store');

            Route::prefix('{schoolClass}')->group(function () {
                Route::get('/', [SchoolClassController::class, 'show'])->name('show');
                Route::get('/edit', [SchoolClassController::class, 'edit'])->name('edit');
                Route::put('/', [SchoolClassController::class, 'update'])->name('update');
                Route::delete('/', [SchoolClassController::class, 'destroy'])->name('destroy');

                // Nested routes for student assignments
                Route::prefix('students')->name('students.')->group(function () {
                    Route::post('/', [ClassStudentController::class, 'store'])->name('store');
                    Route::get('/{assignment}/edit', [ClassStudentController::class, 'edit'])->name('edit');
                    Route::put('/{assignment}', [ClassStudentController::class, 'update'])->name('update');
                    Route::delete('/{assignment}', [ClassStudentController::class, 'destroy'])->name('destroy');
                });

                // Nested routes for teacher assignments
                Route::prefix('teachers')->name('teachers.')->group(function () {
                    Route::post('/', [ClassTeacherController::class, 'store'])->name('store');
                    Route::get('/{assignment}/edit', [ClassTeacherController::class, 'edit'])->name('edit');
                    Route::put('/{assignment}', [ClassTeacherController::class, 'update'])->name('update');
                    Route::delete('/{assignment}', [ClassTeacherController::class, 'destroy'])->name('destroy');
                });
            });
        });

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
