<?php

namespace App\Http\Controllers;

use App\Models\InvoiceDetail;
use Illuminate\Http\Request;
use App\Models\Invoice;


class InvoiceController extends Controller
{
    // Create a new invoice
    public function createInvoice(Request $request)
    {
        try {
            $invoice = Invoice::create([
                'date' => $request->input('date'),
                'total_price' => $request->input('total_price'),
                'employee_id' => $request->input('employee_id'),
                'customer_id' => $request->input('customer_id'),
                'discount_id' => $request->input('discount_id'),
            ]);

            // Create invoice details
            $invoiceDetails = InvoiceDetail::create(
                [
                    'product_id' => $request->input('product_id'),
                    'invoice_id' => $request->input('invoice_id'),
                    'quantity' => $request->input('quantity'),
                ]
            );

            return response()->json(['success' => true, 'data' => ['invoice' => $invoice, 'invoiceDetail' => $invoiceDetails]], 201);
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
    public function getAllInvoices()
    {
        try {
            $invoices = Invoice::with('invoiceDetails.product')->get();
            return response()->json(['success' => true, 'data' => $invoices], 200);
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
