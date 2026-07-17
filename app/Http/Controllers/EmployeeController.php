<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $items=Item::get();
        return $items;
    }
}
