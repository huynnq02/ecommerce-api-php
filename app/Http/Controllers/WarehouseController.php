<?php
// WarehouseController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;

class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::all();
        return response()->json($warehouses);
    }

    public function show($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        return response()->json($warehouse);
    }

    public function store(Request $request)
    {
        $warehouse = Warehouse::create($request->all());
        return response()->json($warehouse, 201);
    }

    public function update(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->update($request->all());
        return response()->json($warehouse, 200);
    }

    public function destroy($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $warehouse->delete();
        return response()->json(null, 204);
    }
}
