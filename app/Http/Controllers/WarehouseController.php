<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\WarehouseDetail;

class WarehouseController extends Controller
{
    public function createWarehouse(Request $request)
    {
        try {
            $warehouse = Warehouse::create([
                'warehouse_name' => $request->input('warehouse_name'),
                'image' => $request->input('image'),
                'location' => $request->input('location'),
                'employee_id' => $request->input('employee_id'),
            ]);

            $warehouseDetails = $request->input('warehouse_details');

            foreach ($warehouseDetails as $detail) {
                WarehouseDetail::create([
                    'warehouse_id' => $warehouse->warehouse_id,
                    'product_id' => $detail['product_id'],
                    'quantity' => $detail['quantity'],
                    'unit' => $detail['unit'],
                ]);
            }

            return response()->json(['success' => true, 'data' => ['warehouse' => $warehouse, 'warehouseDetails' => $warehouseDetails]], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function updateWarehouse(Request $request, $id)
    {
        try {
            $warehouse = Warehouse::findOrFail($id);

            $warehouse->update([
                'warehouse_name' => $request->input('warehouse_name'),
                'image' => $request->input('image'),
                'location' => $request->input('location'),
                'employee_id' => $request->input('employee_id'),
            ]);

            // Update warehouse details if present in the request
            if ($request->has('warehouse_details')) {
                $warehouseDetails = $request->input('warehouse_details');

                foreach ($warehouseDetails as $detail) {
                    $warehouseDetail = WarehouseDetail::where('warehouse_id', $warehouse->warehouse_id)
                        ->where('product_id', $detail['product_id'])
                        ->first();

                    if ($warehouseDetail) {
                        $warehouseDetail->update([
                            'quantity' => $detail['quantity'],
                            'unit' => $detail['unit'],
                        ]);
                    } else {
                        WarehouseDetail::create([
                            'warehouse_id' => $warehouse->warehouse_id,
                            'product_id' => $detail['product_id'],
                            'quantity' => $detail['quantity'],
                            'unit' => $detail['unit'],
                        ]);
                    }
                }
            }

            return response()->json(['success' => true, 'data' => $warehouse, 'message' => 'Warehouse updated successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Warehouse not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getWarehouse($id)
    {
        try {
            // Lấy thông tin kho cùng với chi tiết kho liên quan từ cơ sở dữ liệu
            $warehouse = Warehouse::with('warehouseDetails.product')->findOrFail($id);
            return response()->json(['success' => true, 'data' => $warehouse], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
    public function getAllWarehouse()
    {
        try {
            // Lấy tất cả các kho cùng với thông tin liên quan
            $warehouses = Warehouse::with('employee', 'supplier', 'warehouseDetails.product')->get();

            return response()->json(['success' => true, 'data' => $warehouses], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function deleteWarehouse($id)
    {
        try {
            $warehouse = Warehouse::findOrFail($id);
            $warehouse->warehouseDetails()->delete();
            $warehouse->delete();

            return response()->json(['success' => true, 'message' => 'Warehouse deleted successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Warehouse not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
