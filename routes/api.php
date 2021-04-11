<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\CompanyController;
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
Route::get('unauthorized', function(){
    return response()->json(['error' => 'Unauthorised'], 401);
})->name('api.unauthorized');
Route::post('register', [ApiAuthController::class, 'register'])->name('api.register');
Route::post('login', [ApiAuthController::class, 'login'])->name('api.login');

Route::middleware('auth:api')->group(function () {
    Route::get('logout', [ApiAuthController::class, 'logout'])->name('api.logout');
    Route::resource('posts', ExpenseController::class);

    Route::group(['prefix' => 'company'],function () {
        Route::get('index', [CompanyController::class, 'index']);
        Route::post('show', [CompanyController::class, 'show']);
    });
});
