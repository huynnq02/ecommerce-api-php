<?php
// WarehouseDetailController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WarehouseDetail;

class WarehouseDetailController extends Controller
{
    public function index()
    {
        $warehouseDetails = WarehouseDetail::all();
        return response()->json($warehouseDetails);
    }

    public function show($id)
    {
        $warehouseDetail = WarehouseDetail::findOrFail($id);
        return response()->json($warehouseDetail);
    }

    public function store(Request $request)
    {
        $warehouseDetail = WarehouseDetail::create($request->all());
        return response()->json($warehouseDetail, 201);
    }

    public function update(Request $request, $id)
    {
        $warehouseDetail = WarehouseDetail::findOrFail($id);
        $warehouseDetail->update($request->all());
        return response()->json($warehouseDetail, 200);
    }

    public function destroy($id)
    {
        $warehouseDetail = WarehouseDetail::findOrFail($id);
        $warehouseDetail->delete();
        return response()->json(null, 204);
    }
}
