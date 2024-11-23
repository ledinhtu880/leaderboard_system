<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GroupController;

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
    return view('index');
});

Route::get('/login', [AuthController::class, 'index']);
Route::get('/create-groups', [GroupController::class, 'createGroups'])->name('createGroups');
Route::get('/admin/dashboard', [GroupController::class, 'adminDashboard'])->name('adminDashboard');
Route::get('/user/dashboard', [GroupController::class, 'userDashboard'])->name('userDashboard');
