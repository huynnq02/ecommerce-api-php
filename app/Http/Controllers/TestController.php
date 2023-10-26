<?php
// app/Http/Controllers/TestController.php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function testConnection()
    {
        $data = Account::all();
        echo $data;
        return response()->json($data);
    }
}
