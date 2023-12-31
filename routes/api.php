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
    Route::post('/login', [AuthController::class, 'login'])->withoutmiddleware(['auth']);;
    Route::get('/profile', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh'])->withoutmiddleware(['auth']);

    // Route::get('/{id}', [AuthController::class, 'getCustomer']);
    // Route::get('/', [AuthController::class, 'getAllCustomers']);
    // Route::delete('/{id}', [AuthController::class, 'deleteCustomer']);
    // Route::put('/{id}', [AuthController::class, 'updateCustomer']);
});


Route::group(['prefix' => 'customers', 'middleware' => 'api',], function () {

    Route::post('/', [CustomerController::class, 'createCustomer'])->withoutmiddleware(['auth']);
    Route::get('/{id}', [CustomerController::class, 'getCustomer']);
    Route::get('/', [CustomerController::class, 'getAllCustomers'])->withoutmiddleware(['auth']);
    Route::delete('/{id}', [CustomerController::class, 'deleteCustomer']);
    Route::put('/{id}', [CustomerController::class, 'updateCustomer']);

    Route::get('/{id}/orders', [CustomerController::class, 'getCustomerOrders'])->withoutmiddleware(['auth']);
    Route::get('/{id}/invoices', [CustomerController::class, 'getCustomerInvoices'])->withoutmiddleware(['auth']);
    Route::get('/{id}/inquiries', [CustomerController::class, 'getCustomerInquiries'])->withoutmiddleware(['auth']);
    Route::get('/{id}/reviews', [CustomerController::class, 'getCustomerReviews'])->withoutmiddleware(['auth']);
    Route::get('/customer/totalAccountInMonth', [CustomerController::class, 'getTotalCustomersInMonth'])->withoutmiddleware(['auth']);
});

route::group(['prefix' => 'products'], function () {
    route::get('/{id}', [productcontroller::class, 'getproduct'])->withoutmiddleware(['auth']);
    route::get('/', [productcontroller::class, 'getAllproducts'])->withoutmiddleware(['auth']);
    route::get('/product/searchProducts', [productcontroller::class, 'searchProduct'])->withoutmiddleware(['auth']);
    route::get('/sell/bestSellProducts', [productcontroller::class, 'getTopSellingProducts'])->withoutmiddleware(['auth']);
    route::get('/rate/topRateProducts', [productcontroller::class, 'getTopRatedProducts'])->withoutmiddleware(['auth']);
    Route::get('/sell/recentProduct', [ProductController::class, 'getRecentlyCreatedProducts'])->withoutmiddleware(['auth']);
    route::get('/similarProduct/{id}', [productcontroller::class, 'getSimilarProducts'])->withoutmiddleware(['auth']);

    route::middleware('api')->group(function () {
        route::post('/', [productcontroller::class, 'createproduct'])->withoutmiddleware(['auth']);
        route::delete('/{id}', [productcontroller::class, 'deleteproduct'])->withoutmiddleware(['auth']);
        route::put('/{id}', [productcontroller::class, 'updateproduct'])->withoutmiddleware(['auth']);
        Route::post('/buy/{id}', [ProductController::class, 'buyProduct'])->withoutmiddleware(['auth']);
    });
});


// route::group(['prefix' => 'categories', 'middleware' => 'api',], function () {
//     Route::get('/{id}', [CategoryController::class, 'getCategory']);
//     Route::get('/', [CategoryController::class, 'getAllCategories']);
//     Route::post('/', [CategoryController::class, 'createCategory']);
//     Route::put('/{id}', [CategoryController::class, 'updateCategory']);
//     Route::delete('/{id}', [CategoryController::class, 'deleteCategory']);
// });
route::group(['prefix' => 'categories'], function () {
    Route::get('/{id}', [CategoryController::class, 'getCategory'])->withoutmiddleware(['auth']);
    Route::get('/', [CategoryController::class, 'getAllCategories'])->withoutmiddleware(['auth']);
    Route::post('/', [CategoryController::class, 'createCategory'])->withoutmiddleware(['auth']);
    Route::put('/{id}', [CategoryController::class, 'updateCategory'])->withoutmiddleware(['auth']);
    Route::delete('/{id}', [CategoryController::class, 'deleteCategory'])->withoutmiddleware(['auth']);
    route::middleware('api')->group(function () {
        // Route::post('/', [CategoryController::class, 'createCategory']);
        // Route::put('/{id}', [CategoryController::class, 'updateCategory']);
        // Route::delete('/{id}', [CategoryController::class, 'deleteCategory']);
    });
});

Route::group(['prefix' => 'discounts', 'middleware' => 'api',], function () {
    Route::get('/', [DiscountController::class, 'getAllDiscounts']);
    Route::get('/{id}', [DiscountController::class, 'getDiscount']);
    Route::post('/', [DiscountController::class, 'createDiscount']);
    Route::put('/{id}', [DiscountController::class, 'updateDiscount']);
    Route::delete('/{id}', [DiscountController::class, 'deleteDiscount']);
});

Route::group(['prefix' => 'carts', 'middleware' => 'api',], function () {
    // Route::get('/{id}', [CartController::class, 'getCart']);
    Route::get('/', [CartController::class, 'getAllCarts'])->withoutmiddleware(['auth']);
    Route::post('/', [CartController::class, 'createCart']);
    Route::patch('/{id}', [CartController::class, 'updateCart'])->withoutmiddleware(['auth']);
    Route::delete('/{id}', [CartController::class, 'deleteCart']);
    Route::delete('/{id}/products/{product_id}', [CartController::class, 'deleteProductFromCart']);
    Route::get('/cart', [CartController::class, 'viewCart'])->withoutmiddleware(['auth']);
    Route::post('/{id}/createOrder', [CartController::class, 'createOrderFromCart'])->withoutmiddleware(['auth']);

    Route::post('/addProduct/{id}', [CartController::class, 'addProductToCart']);
    Route::post('/{id}/addDiscount/{discountId}', [CartController::class, 'addDiscountToCart'])->withoutmiddleware(['auth']);
    Route::delete('discount/{id}', [CartController::class, 'removeDiscountFromCart'])->withoutmiddleware(['auth']);
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
    Route::get('/{id}', [InquiryController::class, 'getInquiry'])->withoutmiddleware(['auth']);
    Route::get('/', [InquiryController::class, 'getAllInquiry'])->withoutmiddleware(['auth']);

    Route::middleware('api')->group(function () {
        Route::post('/', [InquiryController::class, 'createInquiry']);
        Route::delete('/{id}', [InquiryController::class, 'deleteInquiry']);
        Route::put('/{id}', [InquiryController::class, 'updateInquiry']);
    });
});

Route::group(['prefix' => 'invoice', 'middleware' => 'api',], function () {
    Route::post('/', [InvoiceController::class, 'createInvoice']);
    Route::get('/{id}', [InvoiceController::class, 'getInvoice']);
    Route::get('/', [InvoiceController::class, 'getAllInvoices']);
    Route::delete('/{id}', [InvoiceController::class, 'deleteInvoice']);
    Route::put('/{id}', [InvoiceController::class, 'updateInvoice']);
});

Route::group(['prefix' => 'reviews', 'middleware' => 'api',], function () {
    Route::get('/{id}', [ReviewController::class, 'getReview'])->withoutmiddleware(['auth']);
    Route::get('/', [ReviewController::class, 'getAllReview'])->withoutmiddleware(['auth']);
    Route::middleware('api')->group(function () {
        Route::post('/', [ReviewController::class, 'createReview']);
        Route::delete('/{id}', [ReviewController::class, 'deleteReview']);
        Route::put('/{id}', [ReviewController::class, 'updateReview']);
    });
    Route::get('/review/reviewsByProduct/{productId}', [ReviewController::class, 'getReviewsByProduct'])->withoutmiddleware(['auth']);
});

Route::group([
    'prefix' => 'orders', 'middleware' => 'api',
], function () {
    Route::post('/', [OrderController::class, 'createOrder']);
    Route::get('/{id}', [OrderController::class, 'getOrder'])->withoutmiddleware(['auth']);
    Route::get('/', [OrderController::class, 'getAllOrders'])->withoutmiddleware(['auth']);
    Route::delete('/{id}', [OrderController::class, 'deleteOrder']);
    Route::put('/{id}', [OrderController::class, 'updateOrder']);

    Route::post('/get-coordinates', [OrderController::class, 'getCoordinates'])->withoutmiddleware(['auth']);
    Route::get('/order/customer-history', [OrderController::class, 'getCustomerOrderHistory'])->withoutmiddleware(['auth']);
    Route::get('/order/search', [OrderController::class, 'searchOrder'])->withoutmiddleware(['auth']);
    Route::get('/order/status/{status}', [OrderController::class, 'searchOrderByStatus'])->withoutmiddleware(['auth']);
    Route::get('/order/revenueMonth', [OrderController::class, 'getMonthlyRevenue'])->withoutmiddleware(['auth']);
    Route::get('/order/totalOrderInMonth', [OrderController::class, 'getTotalOrdersInMonth'])->withoutmiddleware(['auth']);
    Route::get('/order/monthRevenue', [OrderController::class, 'getMonthlyRevenueArray'])->withoutmiddleware(['auth']);
    Route::get('/order/latestOrder', [OrderController::class, 'getLatestOrders'])->withoutmiddleware(['auth']);
});

Route::group(['prefix' => 'supplier', 'middleware' => 'api',], function () {
    Route::post('/', [SupplierController::class, 'createSupplier']);
    Route::get('/{id}', [SupplierController::class, 'getSupplier']);
    Route::get('/', [SupplierController::class, 'getAllSupplier']);
    Route::delete('/{id}', [SupplierController::class, 'deleteSupplier']);
    Route::put('/{id}', [SupplierController::class, 'updateSupplier']);
});

Route::group(['prefix' => 'warehouse', 'middleware' => 'api',], function () {
    Route::post('/', [WarehouseController::class, 'createWarehouse'])->withoutmiddleware(['auth']);;
    Route::get('/{id}', [WarehouseController::class, 'getWarehouse']);
    Route::get('/', [WarehouseController::class, 'getAllWarehouse']);
    Route::delete('/{id}', [WarehouseController::class, 'deleteWarehouse']);
    Route::put('/{id}', [WarehouseController::class, 'updateWarehouse']);
});
