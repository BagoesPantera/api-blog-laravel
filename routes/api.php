<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BlogController;

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
// Route::post('login', [AuthController::class, 'login']);
// Route::post('register', [AuthController::class, 'register']);


Route::controller(AuthController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login');
});

Route::controller(BlogController::class)->middleware('auth:sanctum')->prefix('blog')->group(function (){
    Route::get('/', 'index');
    Route::get('random', 'random');
    Route::get('search/{blog}', 'searchBlog');
    Route::get('{blog}', 'show');
    Route::get('{user}', 'byAuthor');
    Route::post('store', 'store');
    Route::put('update', 'update');
    Route::delete('destroy', 'destroy');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
