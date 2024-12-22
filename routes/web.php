<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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

// Route::get('login', [AuthController::class, 'login'])->name('login');
Route::post('checkLogin', [AuthController::class, 'checkLogin'])->name('checkLogin');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('', [HomeController::class, 'leaderboard'])->name('leaderboard');
Route::get('leaderboard/groups', [HomeController::class, 'leaderboardGroup'])->name('leaderboard.group');
Route::middleware('checkLogin')->group(function () {
    Route::get('statistics', [HomeController::class, 'statistics'])->name('statistics');
    Route::get('profile', [HomeController::class, 'memberProfile'])->name('profile');
    // User Route
});
