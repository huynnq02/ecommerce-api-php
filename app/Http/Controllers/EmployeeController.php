<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Constants\PaginationConstants;
use Illuminate\Pagination\LengthAwarePaginator;

class EmployeeController extends Controller
{
    public function createEmployee(Request $request)
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
                'role' => 'employee',
                'avatar' => $request->input('avatar'),
                'created_at' => now(),
            ]);

            $employee = Employee::create([
                'account_id' => $account->account_id,
                'name' => $request->input('name'),
                'phone_number' => $request->input('phone_number'),
                'gender' => $request->input('gender'),
                'birthday' => $request->input('birthday'),
                'address' => $request->input('address'),
            ]);

            return response()->json(['success' => true, 'data' => $employee], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getEmployee($id)
    {
        try {
            $employee = Employee::with('account')->findOrFail($id);
            return response()->json(
                [
                    'success' => true,
                    'data' => $employee
                ],
                200
            );
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Employee not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getAllEmployees(Request $request)
    {
        try {
            $perPage = $request->input('per_page', PaginationConstants::DEFAULT_PER_PAGE); // Số nhân viên trên mỗi trang, mặc định là 10.
            $page = $request->input('page', PaginationConstants::DEFAULT_PAGE); // Trang hiện tại, mặc định là 1.

            // Lấy danh sách nhân viên với tài khoản tương ứng
            $employees = Employee::with('account')->paginate($perPage, ['*'], 'page', $page);

            // Tạo thủ công một paginator response để bao gồm các khóa tùy chỉnh nếu cần.
            $paginator = new LengthAwarePaginator(
                $employees->items(),
                $employees->total(),
                $employees->perPage(),
                $employees->currentPage(),
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
    public function deleteEmployee($id)
    {
        try {
            $employee = Employee::findOrFail($id);
            $employee->delete();

            return response()->json([
                'success' => true,
                'message' => 'Employee deleted successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Employee not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function updateEmployee(Request $request, $id)
    {
        try {
            $employee = Employee::findOrFail($id);

            if ($request->filled(['email', 'password', 'avatar'])) {
                $accountFields = $request->only(['email', 'password', 'avatar']);
                $hashedPassword = bcrypt($accountFields['password']);
                $accountFields['password'] = $hashedPassword;
                $employee->account->update($accountFields);
            }

            if ($request->filled(['name', 'phone_number', 'gender', 'birthday', 'address'])) {
                $employeeFields = $request->only(['name', 'phone_number', 'gender', 'birthday', 'address']);
                $employee->update($employeeFields);
            }

            return response()->json([
                'success' => true,
                'data' => $employee,
                'message' => 'Employee updated successfully'
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Employee not found'], 404);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getEmployeeOrders($id)
    {
        try {
            $employeeOrders = Order::where('employee_id', $id)->get();
            return response()->json([
                'success' => true,
                'data' => $employeeOrders
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getEmployeeInvoices($id)
    {
        try {
            $employeeInvoices = Invoice::where('employee_id', $id)->get();
            return response()->json([
                'success' => true,
                'data' => $employeeInvoices
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
