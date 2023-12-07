<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Constants\PaginationConstants;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderController extends Controller
{
    // Create a new order
    public function createOrder(Request $request)
    {
        try {
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
            return response()->json(['success' => true, 'data' => ['order' => $order, 'orderDetail' => $orderDetails]], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // Retrieve a specific order by ID
    public function getOrder($id)
    {
        try {
            $order = Order::with('orderDetails.product')->findOrFail($id);
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
}
