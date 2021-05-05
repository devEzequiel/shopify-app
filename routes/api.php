<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
//route user
Route::group(['namespace' => 'Api', 'as' => 'api.'], function () {

    //rotas para controle de usuário
    Route::get('login', [UserController::class, 'login'])->name('login');
    Route::get('logout', [UserController::class, 'logout'])->name('logout');
    Route::get('signup', [UserController::class, 'store'])->name('signup');

    //rotas para controles da lista de desejos
    Route::get('/', [WishListController::class, 'index'])->name('home'); //retorna todas os produtos via api
    Route::get('wishlist/create', [WishListController::class, 'store']);
    Route::get('wishlist', [WishListController::class, 'show']);
    Route::get('wishlist/delete/{id}', [WishListController::class, 'destroy']);
});

