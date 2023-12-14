<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Inquiry;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Constants\PaginationConstants;

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

    // public function getAllInquiry()
    // {
    //     try {
    //         $inquiry = Inquiry::orderBy('date', 'desc')->get();
    //         return response()->json(['success' => true, 'data' => $inquiry], 200);
    //     } catch (\Exception $e) {
    //         return response()->json(['error' => $e->getMessage()], 404);
    //     }
    // }
    public function getAllInquiry(Request $request)
    {
        try {
            $perPage = $request->input('per_page', PaginationConstants::DEFAULT_PER_PAGE); // Number of items per page, default to 10.
            $page = $request->input('page', PaginationConstants::DEFAULT_PAGE); // Current page, default to 1.
            $inquiry = Inquiry::with("customer", "employee")->orderBy('date', 'desc')->paginate($perPage, ['*'], 'page', $page);
            // Manually create a paginator response to include custom keys if needed.
            $paginator = new LengthAwarePaginator(
                $inquiry->items(),
                $inquiry->total(),
                $inquiry->perPage(),
                $inquiry->currentPage(),
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return response()->json(
                [
                    'success' => true,
                    'data' => $paginator->items(),
                    'pagination' => [
                        'current_page' => $paginator->currentPage(),
                        'last_page' => $paginator->lastPage(),
                        'total' => $paginator->total(),
                    ],
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
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


