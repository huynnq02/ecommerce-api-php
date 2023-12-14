<?php

namespace App\Http\Controllers;

use App\Constants\PaginationConstants;
use App\Models\InvoiceDetail;
use Illuminate\Http\Request;
use App\Models\Invoice;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    // Create a new invoice
    public function createInvoice(Request $request)
    {
        try {
            $result = DB::transaction(function () use ($request) {
                $invoice = Invoice::create([
                    'date' => $request->input('date'),
                    'total_price' => $request->input('total_price'),
                    'employee_id' => $request->input('employee_id'),
                    'customer_id' => $request->input('customer_id'),
                    'discount_id' => $request->input('discount_id'),
                ]);

                // Create invoice details
                $invoiceDetails = InvoiceDetail::create([
                    'product_id' => $request->input('product_id'),
                    'invoice_id' => $invoice->invoice_id,
                    'quantity' => $request->input('quantity'),
                ]);

                // Return both $invoice and $invoiceDetails
                return ['invoice' => $invoice, 'invoiceDetail' => $invoiceDetails];
            });

            // Access $invoice and $invoiceDetails outside the closure
            return response()->json(['success' => true, 'data' => $result], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    // Retrieve a specific invoice by ID
    public function getInvoice($id)
    {
        try {
            $invoice = Invoice::with('invoiceDetails.product')->findOrFail($id);
            return response()->json(['success' => true, 'data' => $invoice], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    // Retrieve all invoices
    public function getAllInvoices(Request $request)
    {
        try {
            $perPage = $request->input('per_page',  PaginationConstants::DEFAULT_PER_PAGE); // Số hóa đơn trên mỗi trang, mặc định là 10.
            $page = $request->input('page', PaginationConstants::DEFAULT_PAGE); // Trang hiện tại, mặc định là 1.

            // Lấy danh sách hóa đơn với chi tiết và sản phẩm tương ứng
            $invoices = Invoice::with('invoiceDetails.product')->paginate($perPage, ['*'], 'page', $page);

            // Tạo thủ công một paginator response để bao gồm các khóa tùy chỉnh nếu cần.
            $paginator = new LengthAwarePaginator(
                $invoices->items(),
                $invoices->total(),
                $invoices->perPage(),
                $invoices->currentPage(),
                ['path' => $request->url(), 'query' => $request->query()]
            );

            return response()->json([
                'success' => true,
                'data' => $paginator->items(),
                'pagination' => [
                    'current_page' => $paginator->currentPage(),
                    'last_page' => $paginator->lastPage(),
                    'total' => $paginator->total(),
                ],
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // Update an existing invoice by ID
    public function updateInvoice(Request $request, $id)
    {
        try {
            $invoice = Invoice::findOrFail($id);

            $invoice->update([
                'date' => $request->input('date'),
                'total_price' => $request->input('total_price'),
                'employee_id' => $request->input('employee_id'),
                'customer_id' => $request->input('customer_id'),
                'discount_id' => $request->input('discount_id'),
            ]);


            if ($request->filled(['product_id', 'quantity'])) {
                $invoiceDetailFields = $request->only(['product_id', 'quantity']);
                $invoice->invoiceDetails->first()->update($invoiceDetailFields);
            }

            return response()->json(['success' => true, 'data' => $invoice, 'message' => 'Invoice updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }

    // Delete a specific invoice by ID
    public function deleteInvoice($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            $invoice->invoiceDetail()->delete();
            $invoice->delete();

            return response()->json(['success' => true, 'message' => 'Invoice deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 404);
        }
    }
}
