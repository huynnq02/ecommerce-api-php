<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomerController extends Controller
{
    // Display a listing of the customers.
    public function index()
    {
        try {
            $customers = Customer::all();
            return response()->json($customers, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Store a newly created customer in the database.
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'phone_number' => 'required',
                // Add other validation rules as needed
            ]);

            $customer = Customer::create($request->all());
            return response()->json($customer, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Display the specified customer.
    public function show(Customer $customer)
    {
        try {
            return response()->json($customer, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Update the specified customer in the database.
    public function update(Request $request, Customer $customer)
    {
        try {
            $request->validate([
                'name' => 'required',
                'phone_number' => 'required',
                // Add other validation rules as needed
            ]);

            $customer->update($request->all());
            return response()->json($customer, Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Remove the specified customer from the database.
    public function destroy(Customer $customer)
    {
        try {
            $customer->delete();
            return response(null, Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
