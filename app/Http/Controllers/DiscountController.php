<?php
// DiscountController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Discount;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::all();
        return response()->json($discounts);
    }

    public function show($id)
    {
        $discount = Discount::findOrFail($id);
        return response()->json($discount);
    }

    public function store(Request $request)
    {
        $discount = Discount::create($request->all());
        return response()->json($discount, 201);
    }

    public function update(Request $request, $id)
    {
        $discount = Discount::findOrFail($id);
        $discount->update($request->all());
        return response()->json($discount, 200);
    }

    public function destroy($id)
    {
        $discount = Discount::findOrFail($id);
        $discount->delete();
        return response()->json(null, 204);
    }
}
