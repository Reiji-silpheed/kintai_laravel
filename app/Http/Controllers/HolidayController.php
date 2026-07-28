<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class HolidayController extends Controller
{
    public function index(Request $request,Response $response):RedirectResponse|View
    {
        if(session('role_cd')=='general'){
            return redirect('login');
        }
        $holidays=Holiday::get();
        $data=[
            'holidays'=>$holidays
        ];
        return view('holiday.index',$data);
    }
    public function search(Request $request,Response $response):View
    {
        $searchDate=$request->input('searchDate');
        $searchName=$request->input('searchName');
        $holidays=Holiday::query()
        ->when($searchDate,function($query,$searchDate){
            $query->where('yyyymmdd',$searchDate);
        })
        ->when($searchName,function($query,$searchName){
            $query->where('holiday_name','like',"%$searchName%");
        })
        ->get();
        $data=[
            'holidays'=>$holidays
        ];
        return view('holiday.index',$data);
    }
}
