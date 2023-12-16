<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\WarehouseDetail;
use Illuminate\Support\Facades\Log;

use App\Helpers\getCoordinatesHelper;
use function App\Helpers\getCoordinatesHelper;

class WarehouseController extends Controller
{
    public function createWarehouse(Request $request)
    {
        try {
            Log::info("1");
            $data = getCoordinatesHelper($request->input('location'));

            // Add latitude and longitude to the existing location array
            $locationWithLatLon = array_merge($request->input('location'), ['lat' => $data[0], 'lon' => $data[1]]);
            Log::info("2");
            Log::info($locationWithLatLon);

            $warehouse = Warehouse::create([
                'warehouse_name' => $request->input('warehouse_name'),
                'image' => $request->input('image'),
                'location' => $locationWithLatLon,
                'employee_id' => $request->input('employee_id'),
                'description' => $request->input('description'),

            ]);

            $warehouseDetails = $request->input('warehouse_details');

            foreach ($warehouseDetails as $detail) {
                WarehouseDetail::create([
                    'warehouse_id' => $warehouse->warehouse_id,
                    'product_id' => $detail['product_id'],
                    'quantity' => $detail['quantity'],
                    'unit' => $detail['unit'],
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

                // Return both $warehouse and $warehouseDetails
                return ['warehouse' => $warehouse, 'warehouseDetails' => $warehouseDetails];
            }

            // Access $warehouse and $warehouseDetails outside the closure
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
                'description' => $request->input('description'),
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
            $warehouse = Warehouse::with('warehouseDetails.product',"employee")->findOrFail($id);
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
