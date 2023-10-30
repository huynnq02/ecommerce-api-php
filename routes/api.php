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


Route::apiResource('discounts', DiscountController::class);
Route::apiResource('carts', CartController::class);
Route::apiResource('cartdetails', CartDetailController::class);
Route::apiResource('orders', OrderController::class);
Route::apiResource('orderdetails', OrderDetailController::class);
Route::apiResource('invoices', InvoiceController::class);
Route::apiResource('employees', EmployeeController::class);
Route::apiResource('invoicedetails', InvoiceDetailController::class);
Route::apiResource('suppliers', SupplierController::class);
Route::apiResource('warehouses', WarehouseController::class);
Route::apiResource('warehousedetails', WarehouseDetailController::class);
Route::apiResource('reviews', ReviewController::class);
Route::apiResource('inquiries', InquiryController::class);




