<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Constants\PaginationConstants;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductController extends Controller
{
    public function getAllProducts(Request $request)
    {
        try {
            $perPage = $request->input('per_page', PaginationConstants::DEFAULT_PER_PAGE); // Số sản phẩm trên mỗi trang, mặc định là 10.
            $page = $request->input('page', PaginationConstants::DEFAULT_PAGE); // Trang hiện tại, mặc định là 1.
            $products = Product::with('category')->paginate($perPage, ['*'], 'page', $page);

            // Tạo thủ công một paginator response để bao gồm các khóa tùy chỉnh nếu cần.
            $paginator = new LengthAwarePaginator(
                $products->items(),
                $products->total(),
                $products->perPage(),
                $products->currentPage(),
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return response()->json(
                [
                    'success' => true,
                    'data' => $paginator->items(),
                    'pagination' => [
                        'current_page' => $paginator->currentPage(),
                        'last_page' => $paginator->lastPage(),
                        'total' => $paginator->total(),
                    ],
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getProduct($id)
    {
        try {
            $product = Product::with('category')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $product
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createProduct(Request $request)
    {
        try {
            $product = Product::create($request->all());

            return response()->json([
                'success' => true,
                'data' => $product
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
        }
    }

    public function updateProduct(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->update($request->all());

            return response()->json([
                'success' => true,
                'data' => $product
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
        }
    }

    public function deleteProduct($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ], 204);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
    public function buyProduct(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);

            $order = DB::transaction(function () use ($product, $request) {
                $order = Order::create([
                    'customer_id' => auth()->id(),
                    'total_price' => $product->price,
                    'payment_method' => $request->input('payment_method', 'Online Payment'), // Default payment method or get from request
                    'destination' => $request->input('destination', ''), // Default destination or get from request
                    'date' => now(),
                    'status' => Order::DEFAULT_STATUS,
                ]);

                OrderDetail::create([
                    'order_id' => $order->order_id,
                    'product_id' => $product->product_id,
                    'quantity' => $request->input('quantity'),
                    'price' => $product->price,
                ]);

                $product->increment('number_of_sold', $request->input('quantity'));
                $this->updateWarehouse($product, $request->input('quantity'));

                return $order;
            });

            return response()->json([
                'success' => true,
                'data' => $order,
                'message' => 'Product bought successfully',
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
        }
    }
    public function searchProduct(Request $request)
    {
        try {
            $productName = $request->input('product_name');

            $products = Product::with('category')
                ->where('name', 'like', '%' . $productName . '%')
                ->get();

            if ($products->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'Product not found'], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $products
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    //lấy 8 sản phẩm bán chạy nhất
    public function getTopSellingProducts()
    {
        try {

            $topSellingProducts = DB::table('products')
                ->join('order_details', 'products.product_id', '=', 'order_details.product_id')
                ->join('orders', 'order_details.order_id', '=', 'orders.order_id')
                ->select('products.*', DB::raw('SUM(order_details.quantity) as total_quantity_sold'))
                ->groupBy('products.product_id')
                ->orderByDesc('total_quantity_sold')
                ->limit(8)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $topSellingProducts
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
    public function getTopRatedProducts()
    {
        try {

            $topRatedProducts = DB::table('products')
                ->join('reviews', 'products.product_id', '=', 'reviews.product_id')
                ->select('products.*', DB::raw('AVG(reviews.star) as average_rating'))
                ->groupBy('products.product_id')
                ->orderByDesc('average_rating')
                ->limit(9)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $topRatedProducts
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}
