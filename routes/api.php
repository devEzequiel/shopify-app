<?php

use App\Http\Controllers\ConfirmationCodeController;
use App\Http\Controllers\WishListController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

//Route::middleware('auth:sanctum')->any('wishlist', [WishListController::class, 'index'])->name('home');
////    Route::post('/', [WishListController::class, 'index'])->name('home'); //retorna todas os produtos via api
////    Route::get('wishlist/create', [WishListController::class, 'store']);
////    Route::get('wishlist', [WishListController::class, 'show']);
////    Route::get('wishlist/delete/{id}', [WishListController::class, 'destroy']);
////});
////route user
Route::group(['namespace' => 'Wishlist', 'middleware' => ['auth:sanctum'], 'as' => 'wishlist.'], function () {

    //rotas para controles da lista de desejos
    Route::get('/', [WishListController::class, 'products'])->name('home'); //retorna todas os produtos via api
    Route::post('wishlist/create', [WishListController::class, 'store']);
    Route::get('wishlist', [WishListController::class, 'index']);
    Route::delete('wishlist/delete/{id}', [WishListController::class, 'destroy']);
});

Route::group(['namespace' => 'Api', 'as' => 'api.'], function () {

    //rotas para controle de usuário
    Route::post('login', [UserController::class, 'login'])->name('login');
    Route::delete('logout', [UserController::class, 'logout'])->name('logout');
    Route::post('signup', [UserController::class, 'store'])->name('signup');
    Route::post('email-confirmation', [ConfirmationCodeController::class, 'validation'])
        ->name('validate_email');
    Route::post('resend-code', [ConfirmationCodeController::class, 'resend'])
        ->name('resend_code');
});

