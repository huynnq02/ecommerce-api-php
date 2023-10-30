<?php
// InvoiceController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::all();
        return response()->json($invoices);
    }

    public function show($id)
    {
        $invoice = Invoice::findOrFail($id);
        return response()->json($invoice);
    }

    public function store(Request $request)
    {
        $invoice = Invoice::create($request->all());
        return response()->json($invoice, 201);
    }

    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update($request->all());
        return response()->json($invoice, 200);
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        return response()->json(null, 204);
    }
}
