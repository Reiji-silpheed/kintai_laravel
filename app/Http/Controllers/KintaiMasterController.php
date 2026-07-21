<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class KintaiMasterController extends Controller
{
    public function index(Request $request,Response $response):View
    {
        return view('kintai_master.index');
    }
}
