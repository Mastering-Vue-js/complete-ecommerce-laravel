<?php

use App\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
  return $request->user();
});

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

Route::get('products', [ProductController::class, 'getProducts']);
Route::get('categories', [CategoryController::class, 'getCategories']);

Route::group(['prefix' => 'admin'], function () {
  Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::group(['middleware' => 'admin'], function () {
      Route::get('users', [AuthController::class, 'getUsers']);
      Route::post('users/add', [AuthController::class, 'addUser']);
      Route::post('users/update', [AuthController::class, 'updateUser']);
      Route::delete('users/delete/{id}', [AuthController::class, 'deleteUser']);

      Route::post('categories/add', [CategoryController::class, 'addCategory']);
      Route::delete('categories/delete/{id}', [CategoryController::class, 'deleteCategory']);

      Route::post('products/add', [ProductController::class, 'addProduct']);
      Route::post('products/update', [ProductController::class, 'updateProduct']);
      Route::delete('products/delete/{id}', [ProductController::class, 'deleteProduct']);

      Route::get('coupon', [CouponController::class, 'getCoupons']);
      Route::post('coupon/add', [CouponController::class, 'addCoupons']);
      Route::post('coupon/update', [CouponController::class, 'updateCoupons']);
      Route::delete('coupon/delete/{id}', [CouponController::class, 'deleteCoupons']);
    });
  });
});

Route::group(['middleware' => 'auth:sanctum'], function () {
  Route::get('logout', [AuthController::class, 'logout']);
  Route::get('user', [AuthController::class, 'user']);
});
