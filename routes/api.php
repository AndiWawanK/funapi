<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\Auth\LoginController;
use App\Http\Controllers\API\Auth\RegisterController;
use App\Http\Controllers\API\TodoController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'prefix' => 'v1'
], function(){
    Route::post('/login', [LoginController::class, 'index']);
    Route::post('/register', [RegisterController::class, 'index']);
});
Route::group([
    'middleware' => 'auth:sanctum',
    'prefix' => 'v1'
], function(){
    Route::get('/todo', [TodoController::class, 'showAll']);
    Route::post('/todo/new', [TodoController::class, 'create']);
    Route::get('/todo/{todoId}', [TodoController::class, 'detail']);
    Route::delete('/todo/{todoId}', [TodoController::class, 'delete']);
    Route::put('/todo/{todoId}', [TodoController::class, 'update']);
    Route::put('/todo/{todoId}/complete', [TodoController::class, 'complete']);
});