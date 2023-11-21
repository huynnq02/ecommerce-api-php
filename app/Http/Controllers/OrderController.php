<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;

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
    public function getAllOrders()
    {
        try {
            $orders = Order::with('orderDetails.product')->get();
            return response()->json(['success' => true, 'data' => $orders], 200);
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
