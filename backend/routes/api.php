<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\QuestionsController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\UserController;
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

Route::post('/account/register', [UserController::class, 'store']);
Route::post('/account/login', [UserController::class, 'index']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/account/user', [UserController::class, 'show']);
    Route::delete('/account/logout', [UserController::class, 'destroy']);
    Route::post('/user/avatar', [UserController::class, 'avatar']);
    Route::get('/user/questions', [UserController::class, 'question']);

    Route::post('/question/new', [QuestionsController::class, 'store']);
    Route::delete('/question/{id}/delete', [QuestionsController::class, 'destroy']);
    Route::get('/questions', [QuestionsController::class, 'show']);
    Route::get('/question/{id}', [QuestionsController::class, 'find']);

    Route::post('/response/add', [ResponseController::class, 'store']);

    Route::post('/likes', [LikeController::class, 'store']);

    Route::get('/admin/questions', [AdminController::class, 'index']);
    Route::post('/admin/questions/delete', [AdminController::class, '_delete']);
    Route::get('/admin/users', [AdminController::class, 'show']);
    Route::post('/admin/users/banned', [AdminController::class, 'destroy']);
    Route::post('/admin/users/activate', [AdminController::class, 'activate']);
});
