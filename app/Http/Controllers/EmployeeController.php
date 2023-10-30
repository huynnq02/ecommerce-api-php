<?php 
// EmployeeController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return response()->json($employees);
    }

    public function show($id)
    {
        $employee = Employee::findOrFail($id);
        return response()->json($employee);
    }

    public function store(Request $request)
    {
        $employee = Employee::create($request->all());
        return response()->json($employee, 201);
    }

    public function update(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);
        $employee->update($request->all());
        return response()->json($employee, 200);
    }

    public function destroy($id)
    {
        $employee = Employee::findOrFail($id);
        $employee->delete();
        return response()->json(null, 204);
    }
}
