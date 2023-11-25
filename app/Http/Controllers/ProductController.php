<?php

namespace App\Http\Controllers;

use App\Constants\PaginationConstants;
use Illuminate\Http\Request;
use App\Models\Product;
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
}
