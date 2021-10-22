<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContinentController;

use App\Http\Controllers\DestinationController;

use App\Http\Controllers\MessageController;
use App\Http\Controllers\AuthController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::post('/me', [AuthController::class, 'me']);
    Route::post('/register', [AuthController::class, 'register']);

});

Route::group([

    'middleware' => 'jwt.auth',
    'prefix' => 'test'

], function ($router) {

    Route::post('/continents',[ContinentController::class, 'store']); 
    Route::put('continents/{id}', [ContinentController::class,'update']);
    Route::delete('continents/{id}', [ContinentController::class,'destroy']);
     
    Route::put('destinations/{id}', [DestinationController::class,'update']);
   
    Route::get('/messages',[MessageController::class, 'index']);
});

Route::get('/continents',[ContinentController::class, 'index']);

Route::get('continents/{id}', [ContinentController::class,'show']);

Route::get('/destinations',[DestinationController::class, 'index']);

Route::get('destinations/{id}', [DestinationController::class,'show']);
Route::post('/destinations',[DestinationController::class, 'store']);
Route::post('/messages',[MessageController::class, 'store']);
Route::delete('destinations/{id}', [DestinationController::class,'destroy']);