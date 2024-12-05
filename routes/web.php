<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TopicController;

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
Route::get('register', [AuthController::class, 'register'])->name('register');
Route::post('handleRegister', [AuthController::class, 'handleRegister'])->name('handleRegister');
Route::post('login', [AuthController::class, 'checkLogin'])->name('checkLogin');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('checkLogin')->group(function () {
    // Admin Route
    Route::middleware(['checkPermission'])->group(function () {
        Route::get('admin/groups', [HomeController::class, 'groupManagement'])->name('admin.groups');
        Route::get('admin/members', [HomeController::class, 'memberManagement'])->name('admin.members');
        Route::get('admin/topics', [HomeController::class, 'topicManagement'])->name('admin.topics');
    });

    // User Route
    Route::get('', [HomeController::class, 'index'])->name('home');
    Route::get('user/topics', [HomeController::class, 'userTopic'])->name('user.topics');
});
