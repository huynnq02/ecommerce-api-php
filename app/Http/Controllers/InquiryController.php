<?php
// InquiryController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inquiry;

class InquiryController extends Controller
{
    public function index()
    {
        $inquiries = Inquiry::all();
        return response()->json($inquiries);
    }

    public function show($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        return response()->json($inquiry);
    }

    public function store(Request $request)
    {
        $inquiry = Inquiry::create($request->all());
        return response()->json($inquiry, 201);
    }

    public function update(Request $request, $id)
    {
        $inquiry = Inquiry::findOrFail($id);
        $inquiry->update($request->all());
        return response()->json($inquiry, 200);
    }

    public function destroy($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        $inquiry->delete();
        return response()->json(null, 204);
    }
}
