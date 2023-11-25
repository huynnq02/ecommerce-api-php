<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;

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
    public function getAllReview()
    {
        try {
            // Lấy tất cả các đánh giá kèm theo thông tin liên quan
            $reviews = Review::with('product', 'customer')->get();

            return response()->json(['success' => true, 'data' => $reviews], 200);
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
}
