<?php
// CartController.php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Contracts\Providers\Auth;

class CartController extends Controller
{
    public function createCart(Request $request)
    {
        try {
            $cart = DB::transaction(function () use ($request) {
                $cart = Cart::create([
                    'customer_id' => $request->input('customer_id'),
                    'discount_id' => $request->input('discount_id'),
                    'total_price' => $request->input('total_price'),
                ]);

                // Create cart detail
                $cartDetail = CartDetail::create([
                    'cart_id' => $cart->cart_id,
                    'product_id' => $request->input('product_id'),
                    'quantity' => $request->input('quantity'),
                ]);

                return ['cart' => $cart, 'cart_detail' => $cartDetail];
            });

            return response()->json(['success' => true, 'data' => ['cart' => $cart]], 201);
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
            DB::beginTransaction();

            $cart = Cart::findOrFail($id);

            if ($request->filled(['customer_id', 'discount_id', 'total_price'])) {
                $cartFields = $request->only(['customer_id', 'discount_id', 'total_price']);
                $cart->update($cartFields);
            }

            if ($request->filled(['product_id', 'quantity'])) {
                $product_id = $request->input('product_id');
                $quantity = $request->input('quantity');

                $cartDetail = $cart->cartDetails()->where('product_id', $product_id)->first();
                Log::info($cartDetail);
                if ($cartDetail) {
                    if ($quantity > 0) {
                        $cartDetail->update(['quantity' => $quantity]);
                    } else {
                        $cartDetail->delete();
                    }
                } else {
                    // If cart detail does not exist, create a new one
                    CartDetail::create([
                        'cart_id' => $cart->cart_id,
                        'product_id' => $product_id,
                        'quantity' => $quantity,
                    ]);
                }
            }
            $cart->refresh();
            $totalPrice = $cart->cartDetails->sum(function ($detail) {
                return $detail->quantity * $detail->product->price;
            });
            $cart->update(['total_price' => $totalPrice]);
            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $cart,
                'message' => 'Cart updated successfully',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Cart not found'], 404);
        } catch (\Exception $e) {
            DB::rollBack();
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
    public function deleteProductFromCart($id, $product_id)
    {
        try {
            $cart = Cart::findOrFail($id);

            $cartDetail = $cart->cartDetails->where('product_id', $product_id)->first();

            if ($cartDetail) {
                $cartDetail->delete();

                $cart->refresh();
                $totalPrice = $cart->cartDetails->sum(function ($detail) {
                    return $detail->quantity * $detail->product->price;
                });
                $cart->update(['total_price' => $totalPrice]);

                return response()->json([
                    'success' => true,
                    'data' => $cart,
                    'message' => 'Product deleted from the cart successfully',
                ], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'Product not found in the cart'], 404);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Cart not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function viewCart()
    {
        try {
            $user = auth('api')->user();
            Log::info($user);
            if (!$user || !$user->customer) {
                return response()->json(['success' => false, 'message' => 'Customer not found'], 404);
            }

            $cart = Cart::where('customer_id', $user->customer->customer_id)->with(['cartDetails.product', 'discount'])->first();

            if ($cart) {
                $response = ['success' => true, 'data' => ['cart' => $cart]];

                if ($cart->discount) {
                    $response['data']['discount_value'] = $cart->discount->discount_value;
                }

                return response()->json($response, 200);
            } else {
                return response()->json(['success' => false, 'message' => 'Cart not found'], 404);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function addProductToCart($id)
    {
        try {
            $user = auth('api')->user();


            if (!$user || !$user->customer) {
                return response()->json(['success' => false, 'message' => 'Customer not found'], 404);
            }

            $product = Product::findOrFail($id);


            if (!$product) {
                return response()->json(['success' => false, 'message' => 'Product not found'], 404);
            }


            $cart = Cart::where('customer_id', $user->customer->customer_id)->with(['cartDetails.product', 'discount'])->first();

            if (!$cart) {
                // Nếu giỏ hàng không tồn tại, tạo mới
                $cart = Cart::create([
                    'customer_id' => $user->customer->customer_id,
                    'discount_id' => null,
                    'total_price' => 0,
                ]);
            }

            // Thêm sản phẩm vào giỏ hàng hoặc tăng số lượng nếu đã tồn tại
            $cartDetail = $cart->cartDetails->where('product_id', $product->product_id)->first();

            if ($cartDetail) {
                $cartDetail->update(['quantity' => $cartDetail->quantity + 1]);
            } else {
                CartDetail::create([
                    'cart_id' => $cart->cart_id,
                    'product_id' => $product->product_id,
                    'quantity' => 1,
                ]);
            }

            // Cập nhật lại giá total_price trong giỏ hàng
            $cart->refresh();
            $totalPrice = $cart->cartDetails->sum(function ($detail) {
                return $detail->quantity * $detail->product->price;
            });
            $cart->update(['total_price' => $totalPrice]);

            return response()->json([
                'success' => true,
                'data' => $cart,
                'message' => 'Product added to the cart successfully',
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function addDiscountToCart($discountId, $id)
    {
        try {
            $cart = Cart::findOrFail($id);
            if (!$cart) {
                return response()->json(['success' => false, 'message' => 'Cart not found'], 404);
            }

            $discount = Discount::findOrFail($discountId);

            if (!$discount) {
                return response()->json(['success' => false, 'message' => 'Discount not found'], 404);
            }

            // Cập nhật discount_id trong giỏ hàng
            $cart->update(['discount_id' => $discountId]);

            // Trả về thông tin giỏ hàng sau khi thêm discount
            $cart->refresh();
            $totalPrice = $cart->cartDetails->sum(function ($detail) {
                return $detail->quantity * $detail->product->price;
            });
            $cart->update(['total_price' => $totalPrice]);

            return response()->json([
                'success' => true,
                'data' => $cart,
                'message' => 'Discount added to the cart successfully',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Cart or Discount not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function removeDiscountFromCart($id)
    {
        try {
            $cart = Cart::findOrFail($id);

            if ($cart->discount_id) {
                $cart->update(['discount_id' => null]);
                $cart->refresh(); // Refresh the model to get the updated data

                return response()->json([
                    'success' => true,
                    'data' => $cart,
                    'message' => 'Discount removed from the cart successfully',
                ], 200);
            } else {
                return response()->json(['success' => false, 'message' => 'No discount found in the cart'], 404);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Cart not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
