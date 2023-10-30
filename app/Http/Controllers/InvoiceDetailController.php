<?php
// InvoiceDetailController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InvoiceDetail;

class InvoiceDetailController extends Controller
{
    public function index()
    {
        $invoiceDetails = InvoiceDetail::all();
        return response()->json($invoiceDetails);
    }

    public function show($id)
    {
        $invoiceDetail = InvoiceDetail::findOrFail($id);
        return response()->json($invoiceDetail);
    }

    public function store(Request $request)
    {
        $invoiceDetail = InvoiceDetail::create($request->all());
        return response()->json($invoiceDetail, 201);
    }

    public function update(Request $request, $id)
    {
        $invoiceDetail = InvoiceDetail::findOrFail($id);
        $invoiceDetail->update($request->all());
        return response()->json($invoiceDetail, 200);
    }

    public function destroy($id)
    {
        $invoiceDetail = InvoiceDetail::findOrFail($id);
        $invoiceDetail->delete();
        return response()->json(null, 204);
    }
}
