<?php
// OrderDetailController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderDetail;

class OrderDetailController extends Controller
{
    public function index()
    {
        $orderDetails = OrderDetail::all();
        return response()->json($orderDetails);
    }

    public function show($id)
    {
        $orderDetail = OrderDetail::findOrFail($id);
        return response()->json($orderDetail);
    }

    public function store(Request $request)
    {
        $orderDetail = OrderDetail::create($request->all());
        return response()->json($orderDetail, 201);
    }

    public function update(Request $request, $id)
    {
        $orderDetail = OrderDetail::findOrFail($id);
        $orderDetail->update($request->all());
        return response()->json($orderDetail, 200);
    }

    public function destroy($id)
    {
        $orderDetail = OrderDetail::findOrFail($id);
        $orderDetail->delete();
        return response()->json(null, 204);
    }
}
