<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\EmployeeAddRequest;
use App\Http\Requests\EmployeeEditRequest;
use Carbon\Carbon;


class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $items=User::get();
        $startItems=User::orderBy('id','asc')->offset(0)->limit(5)->get();
        return compact('items','startItems');
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
        ->when(!is_null($request->input('searchRole_cd')), function ($query) use ($searchRole_cd) {
            return $query->where('role_cd', $searchRole_cd);
        })
        ->get();
        $startItems=User::query()
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
        ->when(!is_null($request->input('searchRole_cd')), function ($query) use ($searchRole_cd) {
            return $query->where('role_cd', $searchRole_cd);
        })
        ->orderBy('id','asc')->offset(0)->limit(5)->get();
        return compact('items','startItems');
    }
    public function page(Request $request){
        $page=$request->input('page');
        $offset=5*($page-1);
        $searchNumber=$request->input('createSearch.0');
        $searchName=$request->input('createSearch.1');
        $searchEmail=$request->input('createSearch.2');
        $searchDate=$request->input('createSearch.3');
        $searchRole_cd=$request->input('createSearch.4');
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
        ->when(!is_null($request->input('createSearch.4')), function ($query) use ($searchRole_cd) {
            return $query->where('role_cd', $searchRole_cd);
        })
        ->get();
        $startItems=User::query()
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
        ->when(!is_null($request->input('createSearch.4')), function ($query) use ($searchRole_cd) {
            return $query->where('role_cd', $searchRole_cd);
        })
        ->orderBy('id','asc')->offset($offset)->limit(5)->get();
        return compact('items','startItems');
    }
    public function add(EmployeeAddRequest $request){
        DB::beginTransaction();
        try{
            $items=new User();
            $newNumber=$request->input('newNumber');
            $newName=$request->input('newName');
            $newDate=$request->input('newDate');
            $newRole_cd=$request->input('newRole_cd');
            $newEmail=$request->input('newEmail');
            $newPassword=$request->input('newPassword');
            $items->fill([
                'user_no'=>$newNumber,
                'name'=>$newName,
                'start_date'=>$newDate,
                'role_cd'=>$newRole_cd,
                'email'=>$newEmail,
                'password'=>$newPassword
            ]);
            $items->save();
            DB::commit();
        }
        catch(\Exception $ex){
            DB::rollBack();
        }
    }
    public function updateModal(Request $request){
        $id=$request->input('selected');
        $items=User::where('id',$id)->get();
        return $items;
    }
    public function edit(EmployeeEditRequest $request){
        DB::beginTransaction();
        $selected=$request->input('selected');
        $updateNumber=$request->input('updateNumber');
        $updateName=$request->input('updateName');
        $updateDate=$request->input('updateDate');
        $updateRole_cd=$request->input('updateRole_cd');
        $updateEmail=$request->input('updateEmail');
        $updatePassword=$request->input('updatePassword');
        $updateCheckPassword=$request->input('updateCheckPassword');
        try{
            $item=User::find($selected);
            if($updatePassword=="" && $updateCheckPassword==""){
                $item->fill([
                    'user_no'=>$updateNumber,
                    'name'=>$updateName,
                    'start_date'=>$updateDate,
                    'role_cd'=>$updateRole_cd,
                    'email'=>$updateEmail,
                    'updated_at'=>Carbon::now('Asia/Tokyo')
                ]);
            }
            elseif($updatePassword!=="" && $updateCheckPassword!==""){
                $item->fill([
                    'user_no'=>$updateNumber,
                    'name'=>$updateName,
                    'start_date'=>$updateDate,
                    'role_cd'=>$updateRole_cd,
                    'email'=>$updateEmail,
                    'password'=>$updatePassword,
                    'updated_at'=>Carbon::now('Asia/Tokyo')
                ]);
            }
            $item->save();
            DB::commit();
        }
        catch(\Exception $ex){
            DB::rollBack();
            dd($ex->getMessage());
        }
    }
    public function delete(Request $request){
        DB::beginTransaction();
        $selected=$request->input('radio');
        try{
            $count=User::count('id');
            $item=User::find($selected);
            $item->delete();
            DB::commit();
            return $count;
        }
        catch(\Exception $ex){
            DB::rollBack();
        }
    }
}
