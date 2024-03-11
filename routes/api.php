<?php

use Illuminate\Http\Request;
use App\Helpers\Routes\RouteHelper;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
Route::prefix('v1')
    ->group(function(){
        RouteHelper::IncludeRouteFiles(__DIR__ . '/api/v1');
    });
