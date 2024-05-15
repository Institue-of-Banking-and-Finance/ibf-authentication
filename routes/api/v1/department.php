<?php

use App\Http\Controllers\DepartmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('department')->group(function () {
    Route::post('/create',[DepartmentController::class , 'createDepartment'])->name('admin.create.department');
    Route::get('list-all-department' , [DepartmentController::class , 'listAllDepartment'])->name('admin.list.all.department');
    Route::put('update/{id}' , [DepartmentController::class , 'updateDepartment'])->name('admin.update.department');
});
