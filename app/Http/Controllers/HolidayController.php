<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\HolidayAddRequest;

class HolidayController extends Controller
{
    public function index(Request $request,Response $response):RedirectResponse|View
    {
        if(session('role_cd')=='general'){
            return redirect('login');
        }
        $holidays=Holiday::orderBy('id','asc')->offset(0)->limit(5)->get();
        $count=Holiday::count('id');
        $pageNumber=ceil($count/5);
        $request->session()->put('searchDate',"");
        $request->session()->put('searchName',"");
        $searchDate="";
        $searchName="";
        $active=1;
        $request->session()->put('page',$active);
        $data=[
            'holidays'=>$holidays,
            'pageNumber'=>$pageNumber,
            'searchDate'=>$searchDate,
            'searchName'=>$searchName,
            'active'=>$active
        ];
        return view('holiday.index',$data);
    }
    public function searchCondition(Request $request,Response $response):View
    {
        if($request->action==='clear'){
            $holidays=Holiday::orderBy('id','asc')->offset(0)->limit(5)->get();
            $count=Holiday::count('id');
            $pageNumber=ceil($count/5);
            $request->session()->put('searchDate',"");
            $request->session()->put('searchName',"");
            $searchDate="";
            $searchName="";
            $active=1;
            $request->session()->put('page',$active);
            $data=[
                'holidays'=>$holidays,
                'pageNumber'=>$pageNumber,
                'searchDate'=>$searchDate,
                'searchName'=>$searchName,
                'active'=>$active
            ];
            return view('holiday.index',$data);
        }
        else{
            $searchDate=$request->input('searchDate');
            $searchName=$request->input('searchName');
            $request->session()->put('searchDate',$searchDate);
            $request->session()->put('searchName',$searchName);
            $holidays=Holiday::query()
            ->when($searchDate,function($query,$searchDate){
                $query->where('yyyymmdd',$searchDate);
            })
            ->when($searchName,function($query,$searchName){
                $query->where('holiday_name','like',"%$searchName%");
            })
            ->orderBy('id','asc')->offset(0)->limit(5)->get();
            $count=Holiday::query()
            ->when($searchDate,function($query,$searchDate){
                $query->where('yyyymmdd',$searchDate);
            })
            ->when($searchName,function($query,$searchName){
                $query->where('holiday_name','like',"%$searchName%");
            })
            ->count('id');
            $pageNumber=ceil($count/5);
            $active=1;
            $request->session()->put('page',$active);
            $data=[
                'holidays'=>$holidays,
                'pageNumber'=>$pageNumber,
                'searchDate'=>$searchDate,
                'searchName'=>$searchName,
                'active'=>$active
            ];
            return view('holiday.index',$data);
        }

    }
    public function page(Request $request,Response $response,$page):View
    {
        $request->session()->put('page',$page);
        $active=$page;
        $offset=5*($page-1);
        $searchDate=$request->session()->get('searchDate');
        $searchName=$request->session()->get('searchName');
        $holidays=Holiday::query()
        ->when($searchDate,function($query,$searchDate){
            $query->where('yyyymmdd',$searchDate);
        })
        ->when($searchName,function($query,$searchName){
            $query->where('holiday_name','like',"%$searchName%");
        })
        ->orderBy('id','asc')->offset($offset)->limit(5)->get();
        $count=Holiday::query()
        ->when($searchDate,function($query,$searchDate){
            $query->where('yyyymmdd',$searchDate);
        })
        ->when($searchName,function($query,$searchName){
            $query->where('holiday_name','like',"%$searchName%");
        })
        ->count('id');
        $pageNumber=ceil($count/5);
        $data=[
            'holidays'=>$holidays,
            'pageNumber'=>$pageNumber,
            'searchDate'=>$searchDate,
            'searchName'=>$searchName,
            'active'=>$active
        ];
        return view('holiday.index',$data);
    }
    public function pageFront(Request $request,Response $response):View
    {
        $page=$request->session()->get('page');
        $active=$page-1;
        $request->session()->put('page',$active);
        $offset=5*($active-1);
        $searchDate=$request->session()->get('searchDate');
        $searchName=$request->session()->get('searchName');
        $holidays=Holiday::query()
        ->when($searchDate,function($query,$searchDate){
            $query->where('yyyymmdd',$searchDate);
        })
        ->when($searchName,function($query,$searchName){
            $query->where('holiday_name','like',"%$searchName%");
        })
        ->orderBy('id','asc')->offset($offset)->limit(5)->get();
        $count=Holiday::query()
        ->when($searchDate,function($query,$searchDate){
            $query->where('yyyymmdd',$searchDate);
        })
        ->when($searchName,function($query,$searchName){
            $query->where('holiday_name','like',"%$searchName%");
        })
        ->count('id');
        $pageNumber=ceil($count/5);
        $data=[
            'holidays'=>$holidays,
            'pageNumber'=>$pageNumber,
            'searchDate'=>$searchDate,
            'searchName'=>$searchName,
            'active'=>$active
        ];
        return view('holiday.index',$data);
    }
    public function pageNext(Request $request,Response $response):View
    {
        $page=$request->session()->get('page');
        $active=$page+1;
        $request->session()->put('page',$active);
        $offset=5*($active-1);
        $searchDate=$request->session()->get('searchDate');
        $searchName=$request->session()->get('searchName');
        $holidays=Holiday::query()
        ->when($searchDate,function($query,$searchDate){
            $query->where('yyyymmdd',$searchDate);
        })
        ->when($searchName,function($query,$searchName){
            $query->where('holiday_name','like',"%$searchName%");
        })
        ->orderBy('id','asc')->offset($offset)->limit(5)->get();
        $count=Holiday::query()
        ->when($searchDate,function($query,$searchDate){
            $query->where('yyyymmdd',$searchDate);
        })
        ->when($searchName,function($query,$searchName){
            $query->where('holiday_name','like',"%$searchName%");
        })
        ->count('id');
        $pageNumber=ceil($count/5);
        $data=[
            'holidays'=>$holidays,
            'pageNumber'=>$pageNumber,
            'searchDate'=>$searchDate,
            'searchName'=>$searchName,
            'active'=>$active
        ];
        return view('holiday.index',$data);
    }
    public function add(HolidayAddRequest $request,Response $response):RedirectResponse
    {
        DB::beginTransaction();
        $newDate=$request->input('newDate');
        $newName=$request->input('newName');
        $page=$request->session()->get('page');
        try{
            $holidays=new Holiday();
            $holidays->fill([
                'yyyymmdd'=>$newDate,
                'holiday_name'=>$newName
            ]);
            $holidays->save();
            DB::commit();
            return redirect("/holiday/page/{$page}");
        }
        catch(\Exception $ex){
            DB::rollBack();
            return redirect("/holiday/page/{$page}");
        }
    }
}
