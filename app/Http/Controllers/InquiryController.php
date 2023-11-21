<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inquiry;

class InquiryController extends Controller
{
    // Create a new inquiry
    public function createInquiry(Request $request)
    {
        try {
            $inquiry = Inquiry::create([
                "employee_id" => $request->input('employee_id'),
                "date" => $request->input('date'),
                "customer_id" => $request->input('customer_id'),
                "star" => $request->input('star'),
                "content" => $request->input('content'),
            ]);
            return response()->json(['success' => true, 'data' => $inquiry], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // Retrieve a specific inquiry by ID
    public function getInquiry($id)
    {
        try {
            $inquiry = Inquiry::findOrFail($id);
            return response()->json(['success' => true, 'data' => $inquiry], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    public function getAllInquiry()
    {
        try {
            $inquiry = Inquiry::all();
            return response()->json(['success' => true, 'data' => $inquiry], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    // Update an existing inquiry by ID
    public function updateInquiry(Request $request, $id)
    {
        try {
            $inquiry = Inquiry::findOrFail($id);

            $inquiry->update([
                "employee_id" => $request->input('employee_id'),
                "date" => $request->input('date'),
                "customer_id" => $request->input('customer_id'),
                "star" => $request->input('star'),
                "content" => $request->input('content'),
            ]);

            return response()->json([
                'success' => true,
                'data' => $inquiry, 'message' => 'Inquiry updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    // Delete a specific inquiry by ID
    public function deleteInquiry($id)
    {
        try {
            $inquiry = Inquiry::findOrFail($id);
            $inquiry->delete();

            return response()->json(['success' => true, 'message' => 'Inquiry deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
