<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Constants\PaginationConstants;


class ReviewController extends Controller
{
    public function createReview(Request $request)
    {
        try {
            $review = Review::create([
                'date' => $request->input('date'),
                'product_id' => $request->input('product_id'),
                'customer_id' => $request->input('customer_id'),
                'star' => $request->input('star'),
                'content' => $request->input('content'),
            ]);

            return response()->json(['success' => true, 'data' => $review], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    public function getAllReview(Request $request)
    {
        try {
            $perPage = $request->input('per_page', PaginationConstants::DEFAULT_PER_PAGE); // Số sản phẩm trên mỗi trang, mặc định là 10.
            $page = $request->input('page', PaginationConstants::DEFAULT_PAGE); // Trang hiện tại, mặc định là 1.
            $reviews = Review::with('product', 'customer')->orderBy('date', 'desc')->paginate($perPage, ['*'], 'page', $page);

            // Tạo thủ công một paginator response để bao gồm các khóa tùy chỉnh nếu cần.
            $paginator = new LengthAwarePaginator(
                $reviews->items(),
                $reviews->total(),
                $reviews->perPage(),
                $reviews->currentPage(),
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

    public function getReview($id)
    {
        try {
            $review = Review::with('product', 'customer')->findOrFail($id);
            return response()->json(['success' => true, 'data' => $review], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Review not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateReview(Request $request, $id)
    {
        try {
            $review = Review::findOrFail($id);

            $review->update([
                'date' => $request->input('date'),
                'product_id' => $request->input('product_id'),
                'customer_id' => $request->input('customer_id'),
                'star' => $request->input('star'),
                'content' => $request->input('content'),
            ]);

            return response()->json(['success' => true, 'data' => $review, 'message' => 'Review updated successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Review not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteReview($id)
    {
        try {
            $review = Review::findOrFail($id);
            $review->delete();

            return response()->json(['success' => true, 'message' => 'Review deleted successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Review not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getReviewsByProduct(Request $request, $productId)
    {
        try {
            $perPage = $request->input('per_page', PaginationConstants::DEFAULT_PER_PAGE);
            $page = $request->input('page', PaginationConstants::DEFAULT_PAGE);

            $reviews = Review::with('product', 'customer')
                ->where('product_id', $productId)
                ->orderBy('date', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            $paginator = new LengthAwarePaginator(
                $reviews->items(),
                $reviews->total(),
                $reviews->perPage(),
                $reviews->currentPage(),
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
}
