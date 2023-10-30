<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function createCustomer(Request $request)
    {
        try {
            $existingAccount = Account::where('email', $request->input('email'))->first();

            if ($existingAccount) {
                return response()->json(['success' => false, 'error' => 'User with this email already exists'], 409);
            }
            $hashedPassword = bcrypt($request->input('password'));

            $account = Account::create([
                'email' => $request->input('email'),
                'password' => $hashedPassword,
                'role' => 'customer',
                'avatar' => $request->input('avatar'),
                'created_at' => now(),
            ]);

            $customer = Customer::create([
                'account_id' => $account->account_id,
                'name' => $request->input('name'),
                'phone_number' => $request->input('phone_number'),
                'gender' => $request->input('gender'),
                'birthday' => $request->input('birthday'),
                'address' => $request->input('address'),
            ]);

            return response()->json(['success' => true, 'data' => $customer], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getCustomer($id)
    {
        try {
            $customer = Customer::with('account')->findOrFail($id);
            return response()->json(
                [
                    'success' => true,
                    'data' => $customer
                ],
                200
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Customer not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAllCustomers()
    {
        try {
            $customers = Customer::with('account')->get();
            return response()->json(
                [
                    'success' => true,
                    'data' => $customers
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function deleteCustomer($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            $customer->delete();

            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Customer not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updateCustomer(Request $request, $id)
    {
        try {
            $customer = Customer::findOrFail($id);

            if ($request->filled(['email', 'password', 'avatar'])) {
                $accountFields = $request->only(['email', 'password', 'avatar']);
                $hashedPassword = bcrypt($accountFields['password']);
                $accountFields['password'] = $hashedPassword;
                $customer->account->update($accountFields);
            }

            if ($request->filled(['name', 'phone_number', 'gender', 'birthday', 'address'])) {
                $customerFields = $request->only(['name', 'phone_number', 'gender', 'birthday', 'address']);
                $customer->update($customerFields);
            }

            return response()->json([
                'success' => true,
                'data' => $customer,
                'message' => 'Customer updated successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Customer not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
