<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BFIController;
use App\Http\Controllers\Api\AuthController;

Route::prefix('auth')->group(function () {

    // Route::group(['middleware' => ['role:admin']], function () {
    //     Route::post('/create', [BFIController::class, 'create'])->name('admin.create.bfi');
    //     Route::put('/update/{id}', [BFIController::class, 'update'])->name('admin.update.bfi');
    //     Route::get('/get',[BFIController::class , 'listBfi'])->name('admin.list.bfi');
    //  });
    Route::post('/register', [AuthController::class, 'register'])->name('user.register');
    Route::post('/login', [AuthController::class, 'login'])->name('user.login');
    Route::post('/validate-token', [AuthController::class, 'validateToken'])->name('validation.token');
    Route::post('/enroll-user-in-course',[AuthController::class ,'enrollUserInCourse'])->name('enroll.user.inCourse');

    Route::middleware('auth:api')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('user.logout');
         Route::group(['middleware' => ['role:admin']], function () {
            Route::get('/get-user-profile', [AuthController::class, 'listUser'])->name('list.user');
            Route::get('/get-course', [AuthController::class, 'getUserCourse'])->name('get.user.course');
            Route::post('/create/user',[AuthController::class , 'createEmployer'])->name('admin.create.employer');
            Route::get('/get/role',[AuthController::class , 'listRole'])->name('admin.list.role');
         });
    });
});
