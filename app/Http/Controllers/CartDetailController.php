<?php
// CartDetailController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartDetail;

class CartDetailController extends Controller
{
    public function index()
    {
        $cartDetails = CartDetail::all();
        return response()->json($cartDetails);
    }

    public function show($id)
    {
        $cartDetail = CartDetail::findOrFail($id);
        return response()->json($cartDetail);
    }

    public function store(Request $request)
    {
        $cartDetail = CartDetail::create($request->all());
        return response()->json($cartDetail, 201);
    }

    public function update(Request $request, $id)
    {
        $cartDetail = CartDetail::findOrFail($id);
        $cartDetail->update($request->all());
        return response()->json($cartDetail, 200);
    }

    public function destroy($id)
    {
        $cartDetail = CartDetail::findOrFail($id);
        $cartDetail->delete();
        return response()->json(null, 204);
    }
}
