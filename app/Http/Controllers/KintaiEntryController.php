<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class KintaiEntryController extends Controller
{
    public function index(Request $request)
    {
        $items=User::get();
        return $items;
    }
}
