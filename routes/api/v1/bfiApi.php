<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BFIController;
use App\Http\Controllers\Api\AuthController;

Route::prefix('bfi')->group(function () {
    Route::middleware('auth:api')->group(function () {
         Route::group(['middleware' => ['role:admin']], function () {
            Route::post('/create', [BFIController::class, 'create'])->name('admin.create.bfi');
            Route::put('/update/{id}', [BFIController::class, 'update'])->name('admin.update.bfi');
            Route::get('/get',[BFIController::class , 'listBfi'])->name('admin.list.bfi');
         });
    });
});
