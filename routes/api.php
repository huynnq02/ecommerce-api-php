<?php

use App\Http\Controllers\AuthController;
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

Route::group(['prefix' => 'auth', 'middleware' => 'api',], function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/profile', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);

    // Route::get('/{id}', [AuthController::class, 'getCustomer']);
    // Route::get('/', [AuthController::class, 'getAllCustomers']);
    // Route::delete('/{id}', [AuthController::class, 'deleteCustomer']);
    // Route::put('/{id}', [AuthController::class, 'updateCustomer']);
});


Route::group(['prefix' => 'customers', 'middleware' => 'api',], function () {
    Route::post('/', [CustomerController::class, 'createCustomer']);
    Route::get('/{id}', [CustomerController::class, 'getCustomer']);
    Route::get('/', [CustomerController::class, 'getAllCustomers']);
    Route::delete('/{id}', [CustomerController::class, 'deleteCustomer']);
    Route::put('/{id}', [CustomerController::class, 'updateCustomer']);

    Route::get('/{id}/orders', [CustomerController::class, 'getCustomerOrders']);
    Route::get('/{id}/invoices', [CustomerController::class, 'getCustomerInvoices']);
    Route::get('/{id}/inquiries', [CustomerController::class, 'getCustomerInquiries']);
    Route::get('/{id}/reviews', [CustomerController::class, 'getCustomerReviews']);
});

Route::group(['prefix' => 'products', 'middleware' => 'api',], function () {
    Route::get('/', [ProductController::class, 'getAllProducts']);
    Route::get('/{id}', [ProductController::class, 'getProduct']);
    Route::post('/', [ProductController::class, 'createProduct']);
    Route::put('/{id}', [ProductController::class, 'updateProduct']);
    Route::delete('/{id}', [ProductController::class, 'deleteProduct']);
});

Route::group(['prefix' => 'categories', 'middleware' => 'api',], function () {
    Route::get('/{id}', [CategoryController::class, 'getCategory']);
    Route::get('/', [CategoryController::class, 'getAllCategories']);
    Route::post('/', [CategoryController::class, 'createCategory']);
    Route::put('/{id}', [CategoryController::class, 'updateCategory']);
    Route::delete('/{id}', [CategoryController::class, 'deleteCategory']);
});

Route::group(['prefix' => 'discounts', 'middleware' => 'api',], function () {
    Route::get('/', [DiscountController::class, 'getAllDiscounts']);
    Route::get('/{id}', [DiscountController::class, 'getDiscount']);
    Route::post('/', [DiscountController::class, 'createDiscount']);
    Route::put('/{id}', [DiscountController::class, 'updateDiscount']);
    Route::delete('/{id}', [DiscountController::class, 'deleteDiscount']);
});

Route::group(['prefix' => 'carts', 'middleware' => 'api',], function () {
    Route::get('/{id}', [CartController::class, 'getCart']);
    Route::get('/', [CartController::class, 'getAllCarts']);
    Route::post('/', [CartController::class, 'createCart']);
    Route::put('/{id}', [CartController::class, 'updateCart']);
    Route::delete('/{id}', [CartController::class, 'deleteCart']);
});

Route::group(['prefix' => 'employees', 'middleware' => 'api',], function () {
    Route::post('/', [EmployeeController::class, 'createEmployee']);
    Route::get('/{id}', [EmployeeController::class, 'getEmployee']);
    Route::get('/', [EmployeeController::class, 'getAllEmployees']);
    Route::delete('/{id}', [EmployeeController::class, 'deleteEmployee']);
    Route::put('/{id}', [EmployeeController::class, 'updateEmployee']);

    Route::get('/{id}/orders', [EmployeeController::class, 'getEmployeeOrders']);
    Route::get('/{id}/invoices', [EmployeeController::class, 'getEmployeeInvoices']);
});

Route::group(['prefix' => 'inquiry', 'middleware' => 'api',], function () {
    Route::post('/', [InquiryController::class, 'createInquiry']);
    Route::get('/{id}', [InquiryController::class, 'getInquiry']);
    Route::get('/', [InquiryController::class, 'getAllInquiry']);
    Route::delete('/{id}', [InquiryController::class, 'deleteInquiry']);
    Route::put('/{id}', [InquiryController::class, 'updateInquiry']);
});

Route::group(['prefix' => 'invoice', 'middleware' => 'api',], function () {
    Route::post('/', [InvoiceController::class, 'createInvoice']);
    Route::get('/{id}', [InvoiceController::class, 'getInvoice']);
    Route::get('/', [InvoiceController::class, 'getAllInvoices']);
    Route::delete('/{id}', [InvoiceController::class, 'deleteInvoice']);
    Route::put('/{id}', [InvoiceController::class, 'updateInvoice']);
});

Route::group(['prefix' => 'reviews', 'middleware' => 'api',], function () {
    Route::post('/', [ReviewController::class, 'createReview']);
    Route::get('/{id}', [ReviewController::class, 'getReview']);
    Route::get('/', [ReviewController::class, 'getAllReview']);
    Route::delete('/{id}', [ReviewController::class, 'deleteReview']);
    Route::put('/{id}', [ReviewController::class, 'updateReview']);
});

Route::group(['prefix' => 'order', 'middleware' => 'api',], function () {
    Route::post('/', [OrderController::class, 'createOrder']);
    Route::get('/{id}', [OrderController::class, 'getOrder']);
    Route::get('/', [OrderController::class, 'getAllOrders']);
    Route::delete('/{id}', [OrderController::class, 'deleteOrder']);
    Route::put('/{id}', [OrderController::class, 'updateOrder']);
});

Route::group(['prefix' => 'supplier', 'middleware' => 'api',], function () {
    Route::post('/', [SupplierController::class, 'createSupplier']);
    Route::get('/{id}', [SupplierController::class, 'getSupplier']);
    Route::get('/', [SupplierController::class, 'getAllSupplier']);
    Route::delete('/{id}', [SupplierController::class, 'deleteSupplier']);
    Route::put('/{id}', [SupplierController::class, 'updateSupplier']);
});

Route::group(['prefix' => 'warehouse', 'middleware' => 'api',], function () {
    Route::post('/', [WarehouseController::class, 'createWarehouse']);
    Route::get('/{id}', [WarehouseController::class, 'getWarehouse']);
    Route::get('/', [WarehouseController::class, 'getAllWarehouse']);
    Route::delete('/{id}', [WarehouseController::class, 'deleteWarehouse']);
    Route::put('/{id}', [WarehouseController::class, 'updateWarehouse']);
});
