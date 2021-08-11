<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\PeriodController;
use App\Http\Controllers\ManhourController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\LineBotController;
use App\Http\Controllers\todoController;


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

Route::group(['middleware' => 'auth:api'], function(){
  Route::group(['namespace' => 'Cld'], function(): void {
    Route::group(['prefix' => 'calendar'], function(): void {
      Route::get('/index', [CategoryController::class, 'calendarIndex']);
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

  Route::group(['namespace' => 'tsk'], function(): void {
    Route::group(['prefix' => 'task'], function(): void {
      Route::get('index', [TaskController::class, 'index']);
      Route::post('insert', [ManhourController::class, 'insert']);
      Route::post('update', [ManhourController::class, 'update']);
      Route::post('delete', [ManhourController::class, 'delete']);
    });
  });

  Route::group(['namespace' => 'todo'], function(): void { 
    Route::group(['prefix' => 'todo'], function(): void {
      Route::get('index', [todoController::class, 'index']);
      Route::post('update', [todoController::class, 'update']);
    });
  });

});

Route::group(['namespace' => 'line'], function(): void {
  Route::post('/lineCrud', [LineBotController::class, 'lineCrud']);
});