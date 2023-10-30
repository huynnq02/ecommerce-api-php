<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CartDetailController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\InvoiceDetailController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\WarehouseDetailController;
use App\Http\Controllers\ReviewController;
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


Route::prefix('customers')->group(function () {
    Route::post('/', [CustomerController::class, 'createCustomer']);
    Route::get('/{id}', [CustomerController::class, 'getCustomer']);
    Route::get('/', [CustomerController::class, 'getAllCustomers']);
    Route::delete('/{id}', [CustomerController::class, 'deleteCustomer']);
    Route::put('/{id}', [CustomerController::class, 'updateCustomer']);
});


Route::prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'getAllProducts']);
    Route::get('/{id}', [ProductController::class, 'getProduct']);
    Route::post('/', [ProductController::class, 'createProduct']);
    Route::put('/{id}', [ProductController::class, 'updateProduct']);
    Route::delete('/{id}', [ProductController::class, 'deleteProduct']);
});

Route::prefix('categories')->group(function () {
    Route::get('/{id}', [CategoryController::class, 'getCategory']);
    Route::get('/', [CategoryController::class, 'getAllCategories']);
    Route::post('/', [CategoryController::class, 'createCategory']);
    Route::put('/{id}', [CategoryController::class, 'updateCategory']);
    Route::delete('/{id}', [CategoryController::class, 'deleteCategory']);
});

Route::prefix('discounts')->group(function () {
    Route::get('/', [DiscountController::class, 'getAllDiscounts']);
    Route::get('/{id}', [DiscountController::class, 'getDiscount']);
    Route::post('/', [DiscountController::class, 'createDiscount']);
    Route::put('/{id}', [DiscountController::class, 'updateDiscount']);
    Route::delete('/{id}', [DiscountController::class, 'deleteDiscount']);
});

Route::prefix('carts')->group(function () {
    Route::get('/{id}', [CartController::class, 'getCart']);
    Route::get('/', [CartController::class, 'getAllCarts']);
    Route::post('/', [CartController::class, 'createCart']);
    Route::put('/{id}', [CartController::class, 'updateCart']);
    Route::delete('/{id}', [CartController::class, 'deleteCart']);
});

Route::prefix('employees')->group(function () {
    Route::post('/', [EmployeeController::class, 'createEmployee']);
    Route::get('/{id}', [EmployeeController::class, 'getEmployee']);
    Route::get('/', [EmployeeController::class, 'getAllEmployees']);
    Route::delete('/{id}', [EmployeeController::class, 'deleteEmployee']);
    Route::put('/{id}', [EmployeeController::class, 'updateEmployee']);
});
