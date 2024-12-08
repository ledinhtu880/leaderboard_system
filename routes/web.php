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
Route::post('checkLogin', [AuthController::class, 'checkLogin'])->name('checkLogin');
Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('checkLogin')->group(function () {
    // Admin Route
    Route::middleware(['checkPermission'])->group(function () {
        Route::get('admin/members', [HomeController::class, 'memberManagement'])->name('admin.members');
    });

    // User Route
    Route::get('', [HomeController::class, 'index'])->name('home');
    Route::get('member/profile', [HomeController::class, 'memberProfile'])->name('member.profile');
    Route::get('member/calendar', [HomeController::class, 'memberCalendar'])->name('member.calendar');
    Route::get('member/attendance', [HomeController::class, 'memberAttendance'])->name('member.attendance');
});
