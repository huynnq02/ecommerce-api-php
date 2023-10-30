<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function getAllProducts()
    {
        try {
            $products = Product::with('category')->get();

            return response()->json([
                'success' => true,
                'data' => $products
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Product not found'], 404);
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
