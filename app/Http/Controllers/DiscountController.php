<?php
// DiscountController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discount;

class DiscountController extends Controller
{
    public function getAllDiscounts()
    {
        try {
            $discounts = Discount::all();

            return response()->json([
                'success' => true,
                'data' => $discounts
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }

    public function getDiscount($id)
    {
        try {
            $discount = Discount::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $discount
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 404);
        }
    }

    public function createDiscount(Request $request)
    {
        try {
            $existingDiscount = Discount::where('code', $request->input('code'))->first();
            if ($existingDiscount) {
                return response()->json(['success' => false, 'error' => 'Discount code already exists'], 400);
            }

            $discount = Discount::create($request->all());

            return response()->json([
                'success' => true,
                'data' => $discount
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()], 400);
        }
    }

    public function updateDiscount(Request $request, $id)
    {
        try {
            $discount = Discount::findOrFail($id);
            $discount->update($request->all());

            return response()->json([
                'success' => true,
                'data' => $discount
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Discount not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteDiscount($id)
    {
        try {
            $discount = Discount::findOrFail($id);
            $discount->delete();

            return response()->json([
                'success' => true,
                'message' => 'Discount deleted successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Discount not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
