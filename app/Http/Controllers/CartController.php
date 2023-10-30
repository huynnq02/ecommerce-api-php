<?php
// CartController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::all();
        return response()->json($carts);
    }

    public function show($id)
    {
        $cart = Cart::findOrFail($id);
        return response()->json($cart);
    }

    public function store(Request $request)
    {
        $cart = Cart::create($request->all());
        return response()->json($cart, 201);
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::findOrFail($id);
        $cart->update($request->all());
        return response()->json($cart, 200);
    }

    public function destroy($id)
    {
        $cart = Cart::findOrFail($id);
        $cart->delete();
        return response()->json(null, 204);
    }
}
