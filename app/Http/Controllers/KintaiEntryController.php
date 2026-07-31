<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\KintaiEntryDisplayRequest;
use App\Models\User;
use App\Models\Holiday;


class KintaiEntryController extends Controller
{
    public function index(Request $request)
    {
        $items=User::get();
        $holidays=Holiday::get();
        $data=[
            'items'=>$items,
            'holidays'=>$holidays
        ];
        return $data;
    }
    public function display(KintaiEntryDisplayRequest $request)
    {
        $items=User::get();
        $holidays=Holiday::get();
        $data=[
            'items'=>$items,
            'holidays'=>$holidays
        ];
        return $data;
    }
}
