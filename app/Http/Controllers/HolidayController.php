<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\HolidayAddRequest;
use App\Http\Requests\HolidayEditRequest;
use Carbon\Carbon;

class HolidayController extends Controller
{
    public function index(Request $request,Response $response):RedirectResponse|View
    {
        if(session('role_cd')=='general'){
            return redirect('login');
        }
        $holidays=Holiday::orderBy('id','asc')->offset(0)->limit(5)->get();
        $request->session()->put('holidays',$holidays);
        $count=Holiday::count('id');
        $pageNumber=ceil($count/5);
        $request->session()->put('pageNumber',$pageNumber);
        $searchDate="";
        $searchName="";
        $request->session()->put('searchDate',$searchDate);
        $request->session()->put('searchName',$searchName);
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
            $request->session()->put('condition',false);
            $holidays=$request->session()->get('holidays');
            $pageNumber=$request->session()->get('pageNumber');
            $searchDate="";
            $searchName="";
            $active=$request->session()->get('page');
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
            $request->session()->put('condition',true);
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
            $request->session()->put('holidays',$holidays);
            $count=Holiday::query()
            ->when($searchDate,function($query,$searchDate){
                $query->where('yyyymmdd',$searchDate);
            })
            ->when($searchName,function($query,$searchName){
                $query->where('holiday_name','like',"%$searchName%");
            })
            ->count('id');
            $pageNumber=ceil($count/5);
            $request->session()->put('pageNumber',$pageNumber);
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
        if($request->session()->get('condition')==false){
            $searchDate="";
            $searchName="";
        }
        elseif($request->session()->get('condition')==true){
            $searchDate=$request->session()->get('searchDate');
            $searchName=$request->session()->get('searchName');
        }
        $queryDate=$request->session()->get('searchDate');
        $queryName=$request->session()->get('searchName');
        $holidays=Holiday::query()
        ->when($queryDate,function($query,$queryDate){
            $query->where('yyyymmdd',$queryDate);
        })
        ->when($queryName,function($query,$queryName){
            $query->where('holiday_name','like',"%$queryName%");
        })
        ->orderBy('id','asc')->offset($offset)->limit(5)->get();
        $request->session()->put('holidays',$holidays);
        $count=Holiday::query()
        ->when($queryDate,function($query,$queryDate){
            $query->where('yyyymmdd',$queryDate);
        })
        ->when($queryName,function($query,$queryName){
            $query->where('holiday_name','like',"%$queryName%");
        })
        ->count('id');
        $pageNumber=ceil($count/5);
        $request->session()->put('pageNumber',$pageNumber);
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
        if($request->session()->get('condition')==false){
            $searchDate="";
            $searchName="";
        }
        elseif($request->session()->get('condition')==true){
            $searchDate=$request->session()->get('searchDate');
            $searchName=$request->session()->get('searchName');
        }
        $queryDate=$request->session()->get('searchDate');
        $queryName=$request->session()->get('searchName');
        $holidays=Holiday::query()
        ->when($queryDate,function($query,$queryDate){
            $query->where('yyyymmdd',$queryDate);
        })
        ->when($queryName,function($query,$queryName){
            $query->where('holiday_name','like',"%$queryName%");
        })
        ->orderBy('id','asc')->offset($offset)->limit(5)->get();
        $request->session()->put('holidays',$holidays);
        $count=Holiday::query()
        ->when($queryDate,function($query,$queryDate){
            $query->where('yyyymmdd',$searchDate);
        })
        ->when($queryName,function($query,$queryName){
            $query->where('holiday_name','like',"%$queryName%");
        })
        ->count('id');
        $pageNumber=ceil($count/5);
        $request->session()->put('pageNumber',$pageNumber);
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
        if($request->session()->get('condition')==false){
            $searchDate="";
            $searchName="";
        }
        elseif($request->session()->get('condition')==true){
            $searchDate=$request->session()->get('searchDate');
            $searchName=$request->session()->get('searchName');
        }
        $queryDate=$request->session()->get('searchDate');
        $queryName=$request->session()->get('searchName');
        $holidays=Holiday::query()
        ->when($queryDate,function($query,$queryDate){
            $query->where('yyyymmdd',$queryDate);
        })
        ->when($queryName,function($query,$queryName){
            $query->where('holiday_name','like',"%$queryName%");
        })
        ->orderBy('id','asc')->offset($offset)->limit(5)->get();
        $request->session()->put('holidays',$holidays);
        $count=Holiday::query()
        ->when($queryDate,function($query,$queryDate){
            $query->where('yyyymmdd',$queryDate);
        })
        ->when($queryName,function($query,$queryName){
            $query->where('holiday_name','like',"%$queryName%");
        })
        ->count('id');
        $pageNumber=ceil($count/5);
        $request->session()->put('pageNumber',$pageNumber);
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
    public function edit(HolidayEditRequest $request,Response $response):RedirectResponse
    {
        DB::beginTransaction();
        $updateID=$request->input('updateID');
        $updateDate=$request->input('updateDate');
        $updateName=$request->input('updateName');
        $page=$request->session()->get('page');
        try{
            $holidays=Holiday::find($updateID);
            $holidays->fill([
                'yyyymmdd'=>$updateDate,
                'holiday_name'=>$updateName,
                'updated_at'=>Carbon::now('Asia/Tokyo')
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
    public function delete(Request $request,Response $response):RedirectResponse
    {
        DB::beginTransaction();
        $deleteID=$request->input('deleteID');
        $page=$request->session()->get('page');
        try{
            $holidays=Holiday::find($deleteID);
            $holidays->delete();
            DB::commit();
            return redirect("/holiday/page/{$page}");
        }
        catch(\Exception $ex){
            DB::rollBack();
            return redirect("/holiday/page/{$page}");
        }
    }
}
