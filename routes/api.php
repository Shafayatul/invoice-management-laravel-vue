<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ApiAuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\PaymentCategoryController;

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
        Route::post('update-password', [UserController::class, 'UpdatePassword']);

        Route::get('dashboard-data', [DashboardController::class, 'DashboardData']);

        Route::group(['prefix' => 'company'],function () {
            Route::get('index', [CompanyController::class, 'index']);
            Route::post('store', [CompanyController::class, 'store']);
            Route::get('show', [CompanyController::class, 'show']);
            Route::post('update', [CompanyController::class, 'update']);
            Route::get('destroy', [CompanyController::class, 'destroy']);
            Route::get('company-data', [CompanyController::class, 'CompanyData']);
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

        Route::group(['prefix' => 'client'],function () {
            Route::get('index', [ClientController::class, 'index']);
            Route::post('store', [ClientController::class, 'store']);
            Route::get('show', [ClientController::class, 'show']);
            Route::post('update', [ClientController::class, 'update']);
            Route::get('destroy', [ClientController::class, 'destroy']);
            Route::get('client-data', [ClientController::class, 'ClientData']);
        });

        Route::group(['prefix' => 'invoice'],function () {
            Route::get('index', [InvoiceController::class, 'index']);
            Route::post('store', [InvoiceController::class, 'store']);
            Route::get('show', [InvoiceController::class, 'show']);
            Route::post('update', [InvoiceController::class, 'update']);
            Route::get('destroy', [InvoiceController::class, 'destroy']);
            Route::post('paid-invoice', [InvoiceController::class, 'PaidInvoice']);
            Route::get('invoice-history', [InvoiceController::class, 'InvoiceHistory']);
            Route::get('summarized', [InvoiceController::class, 'SummarizedData']);
            Route::get('download/{id}', [InvoiceController::class, 'InvoiceDownload']);
        });

        Route::group(['prefix' => 'income'],function () {
            Route::get('index', [IncomeController::class, 'index']);
            Route::post('store', [IncomeController::class, 'store']);
            Route::get('show', [IncomeController::class, 'show']);
            Route::post('update', [IncomeController::class, 'update']);
            Route::get('destroy', [IncomeController::class, 'destroy']);
        });

        Route::group(['prefix' => 'payment-category'],function () {
            Route::get('index', [PaymentCategoryController::class, 'index']);
            Route::post('store', [PaymentCategoryController::class, 'store']);
            Route::get('show', [PaymentCategoryController::class, 'show']);
            Route::post('update', [PaymentCategoryController::class, 'update']);
            Route::get('destroy', [PaymentCategoryController::class, 'destroy']);
            Route::get('payment-category-data', [PaymentCategoryController::class, 'PaymentCategoryData']);
        });

        Route::group(['prefix' => 'expense'], function () {
            Route::get('index', [ExpenseController::class, 'index']);
            Route::post('store', [ExpenseController::class, 'store']);
            Route::get('show', [ExpenseController::class, 'show']);
            Route::post('update', [ExpenseController::class, 'update']);
            Route::get('destroy', [ExpenseController::class, 'destroy']);
        });
    });
});
