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
use Illuminate\Support\Facades\DB;

use function App\Helpers\getCoordinates;
use function App\Helpers\getCoordinatesHelper;

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
            $order = Order::with('orderDetails.product','customer.account')->findOrFail($id);
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
}
