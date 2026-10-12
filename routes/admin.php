<?php

use App\Http\Controllers\Admin\AbsenceController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ClassStudentController;
use App\Http\Controllers\Admin\ClassTeacherController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PreRegistrationController;
use App\Http\Controllers\Admin\SchoolClassController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherClassController;
use App\Http\Controllers\Admin\TeacherController;
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

        // Student Management Routes
        Route::prefix('students')->name('students.')->group(function () {
            Route::get('/', [StudentController::class, 'index'])->name('index');
            Route::get('/create', [StudentController::class, 'create'])->name('create');
            Route::post('/', [StudentController::class, 'store'])->name('store');
            Route::get('/{student}/edit', [StudentController::class, 'edit'])->name('edit');
            Route::put('/{student}', [StudentController::class, 'update'])->name('update');
            Route::delete('/{student}', [StudentController::class, 'destroy'])->name('destroy');
        });
        // Teacher Management Routes
        Route::resource('teachers', TeacherController::class)
            ->except(['show']);

        // Teacher Attendance Routes (teacher only)
        Route::prefix('my-classes')->name('my-classes.')->group(function () {
            Route::get('/', [TeacherClassController::class, 'index'])->name('index');
            Route::get('/{schoolClass}', [TeacherClassController::class, 'show'])->name('show');
            Route::post('/{schoolClass}/attendance', [TeacherClassController::class, 'storeAttendance'])->name('attendance.store');
        });
        // Pre-Registration Management Routes (manager / deputy only via policy)
        Route::prefix('pre-registrations')->name('pre-registrations.')->group(function () {
            Route::get('/', [PreRegistrationController::class, 'index'])->name('index');
            Route::get('/{preRegistration}', [PreRegistrationController::class, 'show'])->name('show');
            Route::patch('/{preRegistration}/approve', [PreRegistrationController::class, 'approve'])->name('approve');
            Route::patch('/{preRegistration}/reject', [PreRegistrationController::class, 'reject'])->name('reject');
        });

        Route::prefix('absences')->name('absences.')->group(function () {
            Route::get('/', [AbsenceController::class, 'index'])->name('index');
            Route::patch('status', [AbsenceController::class, 'updateStatus'])->name('status');
            Route::post('send-warning', [AbsenceController::class, 'sendWarnings'])->name('send-warning');
        });

        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    });
});
