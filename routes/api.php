<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\ManHourController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Cld'], function(): void {
	Route::group(['prefix' => 'calendar'], function(): void {
		Route::get('/index', [ManHourController::class, 'index']);
	});
});

Route::group(['namespace' => 'Ctg'], function(): void { 
  Route::group(['prefix' => 'category'], function(): void {
		Route::get('{id}/index', [CategoryController::class, 'index']);
		Route::post('update', [CategoryController::class, 'update']);
	});
});

Route::group(['namespace' => 'Prd'], function(): void { 
	Route::group(['prefix' => 'period'], function(): void {
		Route::get('{id}/indexWithItems', [PeriodController::class, 'indexWithItems']);
		Route::get('index', [PeriodController::class, 'index']);
		Route::post('/update', [PeriodController::class, 'update']);
	});
});