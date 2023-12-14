<?php
// CartController.php

namespace App\Http\Controllers;

use App\Models\CartDetail;
use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    public function createCart(Request $request)
    {
        try {
            $cart = Cart::create([
                'customer_id' => $request->input('customer_id'),
                'discount_id' => $request->input('discount_id'),
                'total_price' => $request->input('total_price'),
            ]);
            $cartDetail = CartDetail::create([
                'cart_id' => $cart->cart_id,
                'product_id' => $request->input('product_id'),
                'quantity' => $request->input('quantity'),
            ]);
            return response()->json(['success' => true, 'data' => ['cart' => $cart, 'cart_detail 1' => $cartDetail]], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    public function getAllCarts()
    {
        try {
            $carts = Cart::with('customer', 'discount', 'cartDetails.product')->get();

            return response()->json([
                'success' => true,
                'data' => $carts,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateCart(Request $request, $id)
    {
        try {
            $cart = Cart::findOrFail($id);

            if ($request->filled(['customer_id', 'discount_id', 'total_price'])) {
                $cartFields = $request->only(['customer_id', 'discount_id', 'total_price']);
                $cart->update($cartFields);
            }

            if ($request->filled(['product_id', 'quantity'])) {
                $cartDetailFields = $request->only(['product_id', 'quantity']);
                $cart->cartDetails->first()->update($cartDetailFields);
            }

            return response()->json([
                'success' => true,
                'data' => $cart,
                'message' => 'Cart updated successfully',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Cart not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteCart($id)
    {
        try {
            $cart = Cart::findOrFail($id);
            $cart->delete();

            return response()->json([
                'success' => true,
                'message' => 'Cart deleted successfully',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Cart not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
