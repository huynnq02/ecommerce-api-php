<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function createSupplier(Request $request)
    {
        try {
            $supplier = Supplier::create([
                'name' => $request->input('name'),
                'address' => $request->input('address'),
                'phone_number' => $request->input('phone_number'),
                'email' => $request->input('email'),
            ]);

            return response()->json(['success' => true, 'data' => $supplier], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
    public function getAllSupplier()
    {
        try {
            // Lấy tất cả các nhà cung cấp kèm theo thông tin liên quan
            $suppliers = Supplier::with('warehouses')->get();

            return response()->json(['success' => true, 'data' => $suppliers], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getSupplier($id)
    {
        try {
            $supplier = Supplier::with('warehouses')->findOrFail($id);
            return response()->json(['success' => true, 'data' => $supplier], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Supplier not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateSupplier(Request $request, $id)
    {
        try {
            $supplier = Supplier::findOrFail($id);

            $supplier->update([
                'name' => $request->input('name'),
                'address' => $request->input('address'),
                'phone_number' => $request->input('phone_number'),
                'email' => $request->input('email'),
            ]);

            return response()->json(['success' => true, 'data' => $supplier, 'message' => 'Supplier updated successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Supplier not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteSupplier($id)
    {
        try {
            $supplier = Supplier::findOrFail($id);
            $supplier->warehouses()->delete();
            $supplier->delete();

            return response()->json(['success' => true, 'message' => 'Supplier deleted successfully'], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Supplier not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
