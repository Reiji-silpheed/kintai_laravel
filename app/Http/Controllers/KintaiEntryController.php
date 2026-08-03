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
        $dates=$request->input('date');
        $items=User::get();
        $holidays=Holiday::get();
        $holidayName=[];
        $collection=collect($holidays);
        foreach($dates as $data){
            if($collection->contains('yyyymmdd',$data)){
                /* value():配列の値だけ取得 */
                $holidayName[]=Holiday::where('yyyymmdd',$data)->value('holiday_name');
            }
            else{
                $holidayName[]="";
            }
        }
        $data=[
            'items'=>$items,
            'holidays'=>$holidays,
            'holidayName'=>$holidayName
        ];
        return $data;
    }
    public function display(KintaiEntryDisplayRequest $request)
    {
        $dates=$request->input('date');
        $items=User::get();
        $holidays=Holiday::get();
        $holidayName=[];
        $collection=collect($holidays);
        foreach($dates as $data){
            if($collection->contains('yyyymmdd',$data)){
                /* value():配列の値だけ取得 */
                $holidayName[]=Holiday::where('yyyymmdd',$data)->value('holiday_name');
            }
            else{
                $holidayName[]="";
            }
        }
        $data=[
            'items'=>$items,
            'holidays'=>$holidays,
            'holidayName'=>$holidayName
        ];
        return $data;
    }
}
