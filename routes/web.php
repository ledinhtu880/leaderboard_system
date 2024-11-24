<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;

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

Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('login', [AuthController::class, 'checklogin'])->name('checkLogin');
Route::get('logout',  [AuthController::class, 'logout'])->name('logout');

Route::middleware('checklogin')->group(function () {
    Route::get('', [HomeController::class, 'index']);
    Route::get('cluster/', [GroupController::class, 'clusterView'])->name('clusterView');
    Route::get('run_cluster', [GroupController::class, 'runCluster'])->name('run_cluster');
    Route::get('admin/dashboard', [GroupController::class, 'adminDashboard'])->name('adminDashboard');
    Route::get('user/dashboard', [GroupController::class, 'userDashboard'])->name('userDashboard');
});
