<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use App\Models\AttendanceHead;
use App\Models\AttendanceDetail;
use Illuminate\View\View;
use App\Http\Requests\kintaiMasterApprovalRequest;
use App\Http\Requests\kintaiMasterSendBackRequest;
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
        $page=1;
        $request->session()->put('page',$page);
        $offset=5*($request->session()->get('page')-1);
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
        })->offset($offset)->limit(5)->get();
        $AttendanceHeadCount=AttendanceHead::query()
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
        })->count();
        $AttendanceDetails=AttendanceDetail::all();
        $count=ceil($AttendanceHeadCount/5);
        return view('kintai_master.index',[
            'id'=>session('id'),
            'searchMonth'=>$searchMonth,
            'searchNumber'=>$searchNumber,
            'searchName'=>$searchName,
            'searchStatus'=>$searchStatus,
            'AttendanceHeads'=>$AttendanceHeads,
            'count'=>$count,
            'page'=>$page
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
            $page=$request->session()->get('page');
            $offset=5*($page-1);
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
            })->offset($offset)->limit(5)->get();
            $AttendanceHeadCount=AttendanceHead::query()
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
            })->count();
            $count=ceil($AttendanceHeadCount/5);
            return view('kintai_master.index',[
                'id'=>session('id'),
                'searchMonth'=>$searchMonth,
                'searchNumber'=>$searchNumber,
                'searchName'=>$searchName,
                'searchStatus'=>$searchStatus,
                'AttendanceHeads'=>$AttendanceHeads,
                'page'=>$page,
                'count'=>$count,
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
            $page=1;
            $request->session()->put('page',$page);
            $offset=5*($request->session()->get('page')-1);
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
            })->offset($offset)->limit(5)->get();
            $AttendanceHeadCount=AttendanceHead::query()
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
            })->count();
            $count=ceil($AttendanceHeadCount/5);
            return view('kintai_master.index',[
                'id'=>session('id'),
                'searchMonth'=>$searchMonth,
                'searchNumber'=>$searchNumber,
                'searchName'=>$searchName,
                'searchStatus'=>$searchStatus,
                'AttendanceHeads'=>$AttendanceHeads,
                'page'=>$page,
                'count'=>$count
            ]);
        }
    }
    public function page(Request $request,Response $response,$page):View
    {
        $searchMonth=$request->session()->get('searchMonth');
        $searchNumber=$request->session()->get('searchNumber');
        $searchName=$request->session()->get('searchName');
        $searchStatus=$request->session()->get('searchStatus');
        if(!is_null($request->session()->get('searchMonth'))){
            $yyyymm=Carbon::createFromFormat('Y-m',$request->session()->get('searchMonth'))->format('Ym');
        }
        else{
            $yyyymm=null;
        }
        $request->session()->put('page',$page);
        $offset=5*($page-1);
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
        })->offset($offset)->limit(5)->get();
        $AttendanceHeadCount=AttendanceHead::query()
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
        })->count();
        $count=ceil($AttendanceHeadCount/5);
        return view('kintai_master.index',[
            'id'=>session('id'),
            'searchMonth'=>$searchMonth,
            'searchNumber'=>$searchNumber,
            'searchName'=>$searchName,
            'searchStatus'=>$searchStatus,
            'AttendanceHeads'=>$AttendanceHeads,
            'page'=>$page,
            'count'=>$count
        ]);
    }
    public function page_front(Request $request,Response $response):View
    {
        $searchMonth=$request->session()->get('searchMonth');
        $searchNumber=$request->session()->get('searchNumber');
        $searchName=$request->session()->get('searchName');
        $searchStatus=$request->session()->get('searchStatus');
        if(!is_null($request->session()->get('searchMonth'))){
            $yyyymm=Carbon::createFromFormat('Y-m',$request->session()->get('searchMonth'))->format('Ym');
        }
        else{
            $yyyymm=null;
        }
        $page=$request->session()->get('page')-1;
        $request->session()->put('page',$page);
        $offset=5*($page-1);
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
        })->offset($offset)->limit(5)->get();
        $AttendanceHeadCount=AttendanceHead::query()
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
        })->count();
        $count=ceil($AttendanceHeadCount/5);
        return view('kintai_master.index',[
            'id'=>session('id'),
            'searchMonth'=>$searchMonth,
            'searchNumber'=>$searchNumber,
            'searchName'=>$searchName,
            'searchStatus'=>$searchStatus,
            'AttendanceHeads'=>$AttendanceHeads,
            'page'=>$page,
            'count'=>$count
        ]);
    }
    public function page_next(Request $request,Response $response):View
    {
        $searchMonth=$request->session()->get('searchMonth');
        $searchNumber=$request->session()->get('searchNumber');
        $searchName=$request->session()->get('searchName');
        $searchStatus=$request->session()->get('searchStatus');
        if(!is_null($request->session()->get('searchMonth'))){
            $yyyymm=Carbon::createFromFormat('Y-m',$request->session()->get('searchMonth'))->format('Ym');
        }
        else{
            $yyyymm=null;
        }
        $page=$request->session()->get('page')+1;
        $request->session()->put('page',$page);
        $offset=5*($page-1);
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
        })->offset($offset)->limit(5)->get();
        $AttendanceHeadCount=AttendanceHead::query()
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
        })->count();
        $count=ceil($AttendanceHeadCount/5);
        return view('kintai_master.index',[
            'id'=>session('id'),
            'searchMonth'=>$searchMonth,
            'searchNumber'=>$searchNumber,
            'searchName'=>$searchName,
            'searchStatus'=>$searchStatus,
            'AttendanceHeads'=>$AttendanceHeads,
            'page'=>$page,
            'count'=>$count
        ]);
    }
    public function approval(kintaiMasterApprovalRequest $request,Response $response):RedirectResponse
    {
        $select=$request->input('approvalCheck');
        $checks=array_map('intval', explode(',', $select));
        $page=$request->session()->get('page');
        DB::beginTransaction();
        try{
            foreach($checks as $check){
                $attendance_heads=AttendanceHead::find($check);
                $attendance_heads->fill([
                    'status'=>3
                ]);
                $attendance_heads->save();
                DB::commit();
            }
            return redirect("/kintai_master/page/{$page}")->with('approvalAlert',true);
        }catch (\Exception $ex){
            DB::rollback();
            return redirect("/kintai_master/page/{$page}");
        }
    }
    public function sendBack(kintaiMasterSendBackRequest $request,Response $response):RedirectResponse
    {
        $select=$request->input('sendBackCheck');
        $reject_comment=$request->input('reject_comment');
        $checks=array_map('intval',explode(',',$select));
        $page=$request->session()->get('page');
        DB::beginTransaction();
        try{
            foreach($checks as $check){
                $attendance_heads=AttendanceHead::find($check);
                $attendance_heads->fill([
                    'status'=>2,
                    'reject_comment'=>$reject_comment
                ]);
                $attendance_heads->save();
                DB::commit();
            }
            return redirect("/kintai_mater/page{$page}");
        }
        catch(\Exception $ex){
            DB::rollback();
            return redirect("/kintai_master/page/{$page}");
        }
    }
}
