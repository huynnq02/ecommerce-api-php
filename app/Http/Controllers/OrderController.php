<?php

namespace App\Http\Controllers;

use App\Helpers\getCoordinatesHelper;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Constants\PaginationConstants;
use Illuminate\Http\Client\RequestException;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;


use function App\Helpers\getCoordinates;
use function App\Helpers\getCoordinatesHelper;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Create a new order
    public function createOrder(Request $request)
    {
        try {
            $order = DB::transaction(function () use ($request) {
                $order = Order::create([
                    'customer_id' => $request->input('customer_id'),
                    'total_price' => $request->input('total_price'),
                    'payment_method' => $request->input('payment_method'),
                    'destination' => $request->input('destination'),
                    'date' => $request->input('date'),
                    'status' => $request->input('status'),
                ]);

                // Create order details
                $orderDetails = OrderDetail::create([
                    'product_id' => $request->input('product_id'),
                    'order_id' => $order->order_id,
                    'quantity' => $request->input('quantity'),
                ]);

                $product = Product::find($request->input('product_id'));
                if ($product) {
                    $product->update(['number_of_sold' => $product->number_of_sold + 1]);
                }

                return $order;
            });

            return response()->json(['success' => true, 'data' => ['order' => $order]], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // Retrieve a specific order by ID
    public function getOrder($id)
    {
        try {
            $order = Order::with('orderDetails.product', 'customer.account')->findOrFail($id);
            return response()->json(['success' => true, 'data' => $order], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    // Retrieve all orders
    public function getAllOrders(Request $request)
    {
        try {
            $perPage = $request->input('per_page', PaginationConstants::DEFAULT_PER_PAGE); // Số đơn hàng trên mỗi trang, mặc định là 10.
            $page = $request->input('page', PaginationConstants::DEFAULT_PAGE); // Trang hiện tại, mặc định là 1.

            // Lấy danh sách đơn hàng với chi tiết và sản phẩm tương ứng
            $orders = Order::with('orderDetails.product')->paginate($perPage, ['*'], 'page', $page);

            // Tạo thủ công một paginator response để bao gồm các khóa tùy chỉnh nếu cần.
            $paginator = new LengthAwarePaginator(
                $orders->items(),
                $orders->total(),
                $orders->perPage(),
                $orders->currentPage(),
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return response()->json([
                'success' => true,
                'data' => $paginator->items(),
                'pagination' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'total' => $paginator->total(),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Update an existing order by ID
    public function updateOrder(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);

            $order->update([
                'customer_id' => $request->input('customer_id'),
                'total_price' => $request->input('total_price'),
                'payment_method' => $request->input('payment_method'),
                'destination' => $request->input('destination'),
                'date' => $request->input('date'),
                'status' => $request->input('status'),
            ]);

            if ($request->filled(['product_id', 'quantity'])) {
                $orderDetailFields = $request->only(['product_id', 'quantity']);
                $order->orderDetails->first()->update($orderDetailFields);
            }

            return response()->json(['success' => true, 'data' => $order, 'message' => 'Order updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
    public function updateOrderStatus(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);
        
            $newStatus = $request->input('status');
        
            $allowedStatuses = ['Processing', 'Shipping', 'Complete', 'Cancel'];
        
            if (!in_array($newStatus, $allowedStatuses)) {
                return response()->json(['error' => 'Invalid status value.'], 422);
            }
        
            $order->update([
                'status' => $newStatus,
            ]);
        
            return response()->json(['success' => true, 'data' => $order, 'message' => 'Order updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }

    }
    // Delete a specific order by ID
    public function deleteOrder($id)
    {
        try {
            $order = Order::findOrFail($id);
            $order->orderDetails()->delete();
            $order->delete();

            return response()->json(['success' => true, 'message' => 'Order deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
    // Get latitude and longitude for a destination with nested JSON structure
    public function getCoordinates(Request $request)
    {
        try {
            $location = $request->input('location');
            Log::info("oke");
            $data = getCoordinatesHelper($location);
            Log::info("oke1");

            return response()->json(['success' => true, 'message' => $data], 200);

            // response as response
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getCustomerOrderHistory()
    {
        try {
            $user = auth('api')->user();

            if (!$user || !$user->customer) {
                return response()->json(['success' => false, 'message' => 'Customer not found'], 404);
            }

            $orders = Order::where('customer_id', $user->customer->customer_id)
                ->with('orderDetails.product')
                ->get();

            $formattedOrders = $orders->map(function ($order) {
                $formattedOrderDetails = $order->orderDetails->map(function ($orderDetail) {
                    return [
                        'product_id' => $orderDetail->product_id,
                        'quantity' => $orderDetail->quantity,
                        'product' => $orderDetail->product,
                    ];
                });

                return [
                    'order_id' => $order->order_id,
                    'total_price' => $order->total_price,
                    'payment_method' => $order->payment_method,
                    'destination' => $order->destination,
                    'date' => $order->date,
                    'status' => $order->status,
                    'order_details' => $formattedOrderDetails,
                ];
            });

            return response()->json(['success' => true, 'data' => $formattedOrders], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    // ...

    public function searchOrder(Request $request)
    {
        try {
            $perPage = $request->input('per_page', PaginationConstants::DEFAULT_PER_PAGE);
            $page = $request->input('page', PaginationConstants::DEFAULT_PAGE);

            $keyword = $request->query('keyword');

            $orders = Order::with(['orderDetails.product', 'customer'])
                ->where(function ($query) use ($keyword) {
                    $query->where('order_id', 'like', '%' . $keyword . '%')
                        ->orWhere('customer_id', 'like', '%' . $keyword . '%');
                })
                ->paginate($perPage, ['*'], 'page', $page);

            if ($orders->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Orders not found for the keyword'], 404);
            }

            $paginator = new LengthAwarePaginator(
                $orders->items(),
                $orders->total(),
                $orders->perPage(),
                $orders->currentPage(),
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return response()->json([
                'success' => true,
                'data' => $paginator->items(),
                'pagination' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'total' => $paginator->total(),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function searchOrderByStatus(Request $request, $status)
    {
        try {
            $perPage = $request->input('per_page', PaginationConstants::DEFAULT_PER_PAGE);
            $page = $request->input('page', PaginationConstants::DEFAULT_PAGE);


            if (!in_array($status, Order::VALID_STATUSES)) {
                return response()->json(['success' => false, 'message' => 'Invalid status value'], 400);
            }

            $orders = Order::with(['orderDetails.product', 'customer'])
                ->where('status', $status)
                ->paginate($perPage, ['*'], 'page', $page);

            if ($orders->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Orders not found for the status'], 404);
            }

            $paginator = new LengthAwarePaginator(
                $orders->items(),
                $orders->total(),
                $orders->perPage(),
                $orders->currentPage(),
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return response()->json([
                'success' => true,
                'data' => $paginator->items(),
                'pagination' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'total' => $paginator->total(),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getMonthlyRevenue(Request $request)
    {
        try {

            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;


            $orders = Order::where('status', 'Complete')
                ->whereMonth('date',  $currentMonth)
                ->whereYear('date', $currentYear)
                ->get();


            $totalRevenue = $orders->sum('total_price');

            return response()->json(['success' => true, 'data' => ['total_revenue' => $totalRevenue]], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getTotalOrdersInMonth(Request $request)
    {
        try {

            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;


            $totalOrders = Order::where('status', '<>', 'Canceled')
                ->whereMonth('date', $currentMonth)
                ->whereYear('date', $currentYear)
                ->count();

            return response()->json(['success' => true, 'data' => ['total_orders' => $totalOrders]], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getMonthlyRevenueArray(Request $request)
    {
        try {
            $currentYear = Carbon::now()->year;

            $monthlyRevenueArray = [];

            for ($month = 1; $month <= 12; $month++) {
                $orders = Order::where('status', 'Complete')
                    ->whereMonth('date', $month)
                    ->whereYear('date', $currentYear)
                    ->get();

                $totalRevenue = $orders->sum('total_price');

                // $monthlyRevenueArray[] = [
                //     'month' => $month,
                //     'total_revenue' => $totalRevenue,
                // ];
                $monthlyRevenueArray[] =  $totalRevenue;
            }

            return response()->json(['success' => true, 'data' => $monthlyRevenueArray], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getLatestOrders(Request $request)
    {
        try {
            $latestOrders = Order::with('customer')
                ->orderBy('date', 'desc')
                ->limit(10)
                ->get();

            return response()->json(['success' => true, 'data' => $latestOrders], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
