<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UrlShortenerController;

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

Route::post('url-shortener-guest',[UrlShortenerController::class,'urlShortenerGuest']);

Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function () {

    Route::post('url-shortener-user',[UrlShortenerController::class,'urlShortenerUser']);
    Route::get('get-user-links',[UrlShortenerController::class,'getAllLinks']);
    Route::post('update-link',[UrlShortenerController::class,'updateLink']);
    Route::get('logout',[AuthController::class,'logout']);

    Route::post('link-click',[UrlShortenerController::class,'updateClick']);

});
