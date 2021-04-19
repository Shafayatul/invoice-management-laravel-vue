<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordResetController;

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
Route::group(['prefix' => 'v1'],function () {
    Route::get('unauthorized', function(){
        return response()->json(['error' => 'Unauthorised'], 401);
    })->name('api.unauthorized');
    Route::post('register', [ApiAuthController::class, 'register'])->name('api.register');
    Route::post('login', [ApiAuthController::class, 'login'])->name('api.login');

    Route::group([
        'prefix' => 'password'
      ], function () {
        Route::post('forgot', [PasswordResetController::class, 'forgot']);
        Route::get('check', [PasswordResetController::class, 'check']);
        Route::post('reset', [PasswordResetController::class, 'reset']);
    });
    
    Route::middleware('auth:api')->group(function () {
        
        Route::get('login-user-info', [UserController::class, 'loginUserInfo'])->name('api.login.user.info');
        Route::get('logout', [ApiAuthController::class, 'logout'])->name('api.logout');
        Route::resource('posts', ExpenseController::class);
        Route::post('update-password', [UserController::class, 'UpdatePassword']);

        Route::group(['prefix' => 'company'],function () {
            Route::get('index', [CompanyController::class, 'index']);
            Route::post('store', [CompanyController::class, 'store']);
            Route::get('show', [CompanyController::class, 'show']);
            Route::post('update', [CompanyController::class, 'update']);
            Route::get('destroy', [CompanyController::class, 'destroy']);
        });

        Route::group(['prefix' => 'user'],function () {
            Route::get('index', [UserController::class, 'index']);
            Route::post('store', [UserController::class, 'store']);
            Route::get('show', [UserController::class, 'show']);
            Route::post('update', [UserController::class, 'update']);
            Route::get('destroy', [UserController::class, 'destroy']);
            Route::get('block-or-unblock/{id}', [UserController::class, 'BlockOrUnblockUser']);
            Route::post('assign-company', [UserController::class, 'AssignCompany']);
        });

        Route::group(['prefix' => 'invoice'],function () {
            Route::get('index', [InvoiceController::class, 'index']);
            Route::post('store', [InvoiceController::class, 'store']);
            Route::get('show', [InvoiceController::class, 'show']);
            Route::post('update', [InvoiceController::class, 'update']);
            Route::get('destroy', [InvoiceController::class, 'destroy']);
        });
    });
});
