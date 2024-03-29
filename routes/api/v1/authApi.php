<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register'])->name('user.register');
    Route::post('/login', [AuthController::class, 'login'])->name('user.login');

    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('user.logout');
         Route::group(['middleware' => ['role:admin']], function () {
            Route::get('/get-user-profile', [AuthController::class, 'listUser'])->name('list.user');
            Route::get('/get-course', [AuthController::class, 'getUserCourse'])->name('get.user.course');
         });
    });
});
