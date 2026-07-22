<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $items=User::get();
        return $items;
    }
    public function search(Request $request)
    {
        $searchNumber=$request->input('searchNumber');
        $searchName=$request->input('searchName');
        $searchEmail=$request->input('searchEmail');
        $searchDate=$request->input('searchDate');
        $searchRole_cd=$request->input('searchRole_cd');
        /* 条件があるときだけwhereをつけたいとき、when()メソッドを使う */
        $items=User::query()
        ->when($searchNumber,function($query,$searchNumber){
            return $query->where('user_no',$searchNumber);
        })
        ->when($searchName,function($query,$searchName){
            return $query->where('name','like',"%$searchName%");
        })
        ->when($searchEmail,function($query,$searchEmail){
            return $query->where('email',$searchEmail);
        })
        ->when($searchDate,function($query,$searchDate){
            return $query->where('start_date',$searchDate);
        })
        ->when($searchRole_cd,function($query,$searchRole_cd){
            return $query->where('role_cd',$searchRole_cd);
        })
        ->get();
        return $items;
    }
}
