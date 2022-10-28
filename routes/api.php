<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmailVerificationController;
use App\Http\Controllers\PictureCategoryController;
use App\Http\Controllers\PictureController;
use App\Http\Controllers\VoteController;
use App\Http\Middleware\AdminAuthMiddleware;
use App\Http\Middleware\AuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
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

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);
Route::post('/remember',[AuthController::class,'remember']);


Route::group(['middleware'=>['auth:sanctum']], function () {
    Route::get('/verify-email/{token}',[AuthController::class,'verifyEmail']);

    Route::group(['middleware'=>AuthMiddleware::class],function(){

        Route::get('/logout',[AuthController::class,'logout']);
        Route::get('/user-pictures',[PictureController::class,'userPictures']);
        Route::get('/category',[PictureCategoryController::class,'index']);

        Route::resource('pictures',PictureController::class);
        Route::resource('votes',VoteController::class);

    });

    Route::group(['prefix'=>'admin','middleware'=>AdminAuthMiddleware::class],function(){
        Route::get('/set-user-admin/{id}',[AdminController::class,'setAdmin']);
    });

});

