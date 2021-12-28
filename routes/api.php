<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WebsitesController;
use App\Http\Controllers\Api\SubscriptionController;
use App\Http\Controllers\Api\PostController;
use Illuminate\Support\Facades\Auth;

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


Route::prefix('v1')->group(function () {
    Route::post('login', [AuthController::class, 'login']);
});



Route::prefix('v1')->group(function () {
    Route::resource('websites', WebsitesController::class);
    Route::get('subscriptions', [SubscriptionController::class, 'index']);
    Route::post('subscriptions', [SubscriptionController::class, 'subscribe']);

    Route::resource('posts', PostController::class);
});




Route::prefix('v1')->group(function () {
    Route::middleware('auth:api')->group(function () {
        Route::get('test', [AuthController::class, 'test']);
        Route::get('cached-access-token', [AuthController::class, 'getCachedToken']);
        Route::post('register', [AuthController::class, 'register']);
    });
});



// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
