<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AttendanceHead;
use Illuminate\View\View;
use Carbon\Carbon;

class KintaiMasterController extends Controller
{
    public function index(Request $request,Response $response):View
    {
        $yyyymm=Carbon::now()->format('Ym');
        $searchMonth=Carbon::now()->format('Y-m');
        $request->session()->put('searchMonth',$searchMonth);
        $searchNumber="";
        $request->session()->put('searchNumber',$searchNumber);
        $searchName="";
        $request->session()->put('searchName',$searchName);
        $searchStatus=null;
        $request->session()->put('searchStatus',$searchStatus);
        $AttendanceHeads=AttendanceHead::query()
        ->when($yyyymm,function($query,$yyyymm){
            $query->where('yyyymm',$yyyymm);
        })
        ->when($searchNumber,function($query,$searchNumber){
            $query->whereHas('user',function($q) use ($searchNumber){
                $q->where('user_no',$searchNumber);
            });
        })
        ->when($searchName,function($query,$searchName){
            $query->whereHas('user',function($q) use ($searchName){
                $q->where('name','like',"%$searchName%");
            });
        })
        ->when(!is_null($searchStatus), function ($query) use ($searchStatus) {
            $query->where('status', $searchStatus);
        })->get();
        return view('kintai_master.index',[
            'id'=>session('id'),
            'searchMonth'=>$searchMonth,
            'searchNumber'=>$searchNumber,
            'searchName'=>$searchName,
            'searchStatus'=>$searchStatus,
            'AttendanceHeads'=>$AttendanceHeads
        ]);
    }
    public function searchCondition(Request $request,Response $response):View
    {
        $action=$request->input('action');
        if($action==='clear'){
            $searchMonth="";
            $searchNumber="";
            $searchName="";
            $searchStatus=null;
            if(!is_null($request->session()->get('searchMonth'))){
                $yyyymm=Carbon::createFromFormat('Y-m',$request->session()->get('searchMonth'))->format('Ym');
            }
            else{
                $yyyymm=null;
            }
            $number=$request->session()->get('searchNumber');
            $name=$request->session()->get('searchName');
            $status=$request->session()->get('searchStatus');
            $AttendanceHeads=AttendanceHead::query()
            ->when($yyyymm,function($query,$yyyymm){
                $query->where('yyyymm',$yyyymm);
            })
            ->when($number,function($query,$number){
                $query->whereHas('user',function($q) use ($number){
                    $q->where('user_no',$number);
                });
            })
            ->when($name,function($query,$name){
                $query->whereHas('user',function($q) use ($name){
                    $q->where('name','like',"%$name%");
                });
            })
            ->when(!is_null($status), function ($query) use ($status) {
                $query->where('status', $status);
            })->get();
            return view('kintai_master.index',[
                'id'=>session('id'),
                'searchMonth'=>$searchMonth,
                'searchNumber'=>$searchNumber,
                'searchName'=>$searchName,
                'searchStatus'=>$searchStatus,
                'AttendanceHeads'=>$AttendanceHeads
            ]);
        }
        elseif($action==='search'){
            $searchMonth=$request->input('searchMonth');
            $request->session()->put('searchMonth',$searchMonth);
            $searchNumber=$request->input('searchNumber');
            $request->session()->put('searchNumber',$searchNumber);
            $searchName=$request->input('searchName');
            $request->session()->put('searchName',$searchName);
            $searchStatus=$request->input('searchStatus');
            $request->session()->put('searchStatus',$searchStatus);
            if(!is_null($request->session()->get('searchMonth'))){
                $yyyymm=Carbon::createFromFormat('Y-m',$request->session()->get('searchMonth'))->format('Ym');
            }
            else{
                $yyyymm=null;
            }
            $number=$request->session()->get('searchNumber');
            $name=$request->session()->get('searchName');
            $status=$request->session()->get('searchStatus');
            $AttendanceHeads=AttendanceHead::query()
            ->when($yyyymm,function($query,$yyyymm){
                $query->where('yyyymm',$yyyymm);
            })
            ->when($number,function($query,$number){
                $query->whereHas('user',function($q) use ($number){
                    $q->where('user_no',$number);
                });
            })
            ->when($name,function($query,$name){
                $query->whereHas('user',function($q) use ($name){
                    $q->where('name','like',"%$name%");
                });
            })
            ->when(!is_null($status), function ($query) use ($status) {
                $query->where('status', $status);
            })->get();
            return view('kintai_master.index',[
                'id'=>session('id'),
                'searchMonth'=>$searchMonth,
                'searchNumber'=>$searchNumber,
                'searchName'=>$searchName,
                'searchStatus'=>$searchStatus,
                'AttendanceHeads'=>$AttendanceHeads
            ]);
        }
    }
}
