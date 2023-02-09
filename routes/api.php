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

Route::middleware('auth:sanctum')->group(function(){
    Route::post('makeBlog', [BlogController::class, 'store']);
    Route::get('getRandomBlogs', [BlogController::class, 'random']);
    Route::get('getBlogs', [BlogController::class, 'index']);
    Route::get('getBlogs/{search}', [BlogController::class, 'searchBlog']);
    Route::get('getBlog/{id}', [BlogController::class, 'show']);
    Route::get('userBlog/{author_id}', [BlogController::class, 'getByAuthor']);
    Route::post('updateBlog/{id}', [BlogController::class, 'update']);
    Route::get('deleteBlog/{id}', [BlogController::class, 'destroy']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
