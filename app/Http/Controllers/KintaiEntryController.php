<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\KintaiEntryDisplayRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Holiday;
use App\Models\AttendanceHead;
use App\Models\Arrendance_Detail;


class KintaiEntryController extends Controller
{
    public function index(Request $request)
    {
        $dates=$request->input('date');
        $items=User::get();
        $holidays=Holiday::get();
        $response=Http::get('https://holidays-jp.github.io/api/v1/date.json');
        $nationalHoliday=$response->json();
        $holidayName=[];
        $collection=collect($holidays);
        foreach($dates as $date){
            if($collection->contains('yyyymmdd',$date)){
                /* value():配列の値だけ取得 */
                $holidayName[]=Holiday::where('yyyymmdd',$date)->value('holiday_name');
            }
            elseif(isset($nationalHoliday[$date])){
                $holidayName[]=$nationalHoliday[$date];
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
        $response=Http::get('https://holidays-jp.github.io/api/v1/date.json');
        $nationalHoliday=$response->json();
        $holidayName=[];
        $collection=collect($holidays);
        foreach($dates as $date){
            if($collection->contains('yyyymmdd',$date)){
                /* value():配列の値だけ取得 */
                $holidayName[]=Holiday::where('yyyymmdd',$date)->value('holiday_name');
            }
            elseif(isset($nationalHoliday[$date])){
                $holidayName[]=$nationalHoliday[$date];
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
    public function save(Request $request){
        $id=$request->input('id');
        $save_id=AttendanceHead::where('user_id',$id)->get();
        $yyyymm=$request->input('yyyymm');
        $day=$request->input('day');
        $kbn=$request->input('kbn');
        $start_time=$request->input('start_time');
        $end_time=$request->input('end_time');
        $rest_time=$request->input('rest_time');
        $night_rest_time=$request->input('night_rest_time');
        $work_time=$request->input('work_time');
        $over_time=$request->input('over_time');
        $remarks=$request->input('remarks');
        if($save_id->isEmpty()){
            DB::beginTransaction();
            try{
                $attendance_heads=new AttendanceHead();
                $attendance_heads->fill([
                    'user_id'=>$id,
                    'yyyymm'=>$yyyymm,
                    'status'=>0,
                ]);
                $attendance_heads->save();
                DB::commit();
            }
            catch(\Exception $ex){
                DB::rollBack();
            }
        }
    }
}
