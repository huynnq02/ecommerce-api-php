<?php

namespace App\Http\Controllers;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\Account;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Constants\PaginationConstants;

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

    public function getAllCustomers(Request $request)
    {
        try {
            $perPage = $request->input('per_page', PaginationConstants::DEFAULT_PER_PAGE); // Number of items per page, default to 10.
            $page = $request->input('page', PaginationConstants::DEFAULT_PAGE); // Current page, default to 1.
            $customers = Customer::with('account')->paginate($perPage, ['*'], 'page', $page);
            // Manually create a paginator response to include custom keys if needed.
            $paginator = new LengthAwarePaginator(
                $customers->items(),
                $customers->total(),
                $customers->perPage(),
                $customers->currentPage(),
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
