<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use Illuminate\Http\Request;
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
Route::get('/', function(){
    return response()->json(['message' => 'Connected to server'],200);
});

Route::controller(AuthController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::controller(BlogController::class)->middleware('auth:sanctum')->prefix('blog')->group(function (){
    Route::get('/', 'index');
    Route::get('random', 'random');
    Route::get('search/{blog}', 'search');
    Route::get('{blog}', 'show');
    Route::get('author/{user}', 'byAuthor');
    Route::post('store', 'store');
    Route::put('{blog}', 'update');
    Route::delete('{blog}', 'destroy');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
