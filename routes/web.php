<?php

use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Web\HomeController;
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

Route::group(['middleware' => ['auth.wed']], function () {
    Route::group(['middleware' => ['admin']], function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::group(['prefix' => 'users'], function () {
            Route::get('/', [UserController::class, 'index'])->name('admin.user.index');
            Route::get('/create', [UserController::class, 'create'])->name('admin.user.create');
            Route::get('/edit/{id}', [UserController::class, 'edit'])->name('admin.user.edit');
            Route::post('/store', [UserController::class, 'store'])->name('admin.user.store');
            Route::post('/update/{id}', [UserController::class, 'update'])->name('admin.user.update');
            Route::get('/get-change-password/{id}', [UserController::class, 'getChangePassword'])->name('admin.user.get_change_password');
            Route::post('/change-password/{id}', [UserController::class, 'changePassword'])->name('admin.user.change_password');
            Route::delete('/destroy/{id}', [UserController::class, 'destroy'])->name('admin.user.destroy');
            // Route::logout('/logout', [UserController::class, 'logout'])->name('admin.user.logout');
        });
        Route::group(['prefix' => 'department'], function () {
            Route::get('/', [DepartmentController::class, 'index'])->name('admin.department.index');
            Route::get('/create', [DepartmentController::class, 'create'])->name('admin.department.create');
            Route::get('/edit/{id}', [DepartmentController::class, 'edit'])->name('admin.department.edit');
            Route::post('/store', [DepartmentController::class, 'store'])->name('admin.department.store');
            Route::post('/update/{id}', [DepartmentController::class, 'update'])->name('admin.department.update');
            Route::delete('/destroy/{id}', [DepartmentController::class, 'destroy'])->name('admin.department.destroy');
        });
        Route::group(['prefix' => 'position'], function () {
            Route::get('/', [PositionController::class, 'index'])->name('admin.position.index');
            Route::get('/create', [PositionController::class, 'create'])->name('admin.position.create');
            Route::get('/edit/{id}', [PositionController::class, 'edit'])->name('admin.position.edit');
            Route::post('/store', [PositionController::class, 'store'])->name('admin.position.store');
            Route::post('/update/{id}', [PositionController::class, 'update'])->name('admin.position.update');
            Route::delete('/destroy/{id}', [PositionController::class, 'destroy'])->name('admin.position.destroy');
        });
    });
});

Route::get('register', [RegisterController::class, 'register']);
Route::post('register', [RegisterController::class, 'store'])->name('register');



Route::get('login', [LoginController::class, 'index'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::get('logout', [LoginController::class, 'logout'])->name('logout');
