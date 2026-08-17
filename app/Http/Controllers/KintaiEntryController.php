<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\KintaiEntryDisplayRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Holiday;
use App\Models\AttendanceHead;
use App\Models\AttendanceDetail;
use Carbon\Carbon;



class KintaiEntryController extends Controller
{
    public function index(Request $request)
    {
        $dates=$request->input('date');
        $yyyymm=$request->input('yyyymm');
        $id=$request->input('id');
        $items=User::get();
        $holidays=Holiday::get();
        $attendance_heads=AttendanceHead::where('yyyymm',$yyyymm)->where('user_id',$id)->get();
        $values=AttendanceHead::where('user_id',$id)->get();
        $sendBack=false;
        $sendBack_yyyymm=[];
        $sendBack_comment=[];
        $count=0;
        $response=Http::get('https://holidays-jp.github.io/api/v1/date.json');
        $nationalHoliday=$response->json();
        $holidayName=[];
        $holidayCollection=collect($holidays);
        foreach($values as $value){
            if($value->status==='2'){
                $sendBack=true;
                $sendBack_yyyymm[]=$value->yyyymm;
                $sendBack_comment[]=$value->reject_comment;
                $count+=1;
            }
        }
        foreach($dates as $date){
            if($holidayCollection->contains('yyyymmdd',$date)){
                $holidayName[]=Holiday::where('yyyymmdd',$date)->value('holiday_name');
            }
            elseif(isset($nationalHoliday[$date])){
                $holidayName[]=$nationalHoliday[$date];
            }
            else{
                $holidayName[]="";
            }
        }
        if(!$attendance_heads->isEmpty()){
            $attendance_details=AttendanceDetail::where('attendance_head_id',$attendance_heads[0]->id)->get();
            if($attendance_heads[0]->status==="1" || $attendance_heads[0]->status==="3"){
                $disabled=true;
            }
            else{
                $disabled=false;
            }
        }
        else{
            $attendance_details=[];
            $disabled=false;
        }
        $data=[
            'items'=>$items,
            'holidays'=>$holidays,
            'holidayName'=>$holidayName,
            'attendance_details'=>$attendance_details,
            'disabled'=>$disabled,
            'sendBack'=>$sendBack,
            'sendBack_yyyymm'=>$sendBack_yyyymm,
            'sendBack_comment'=>$sendBack_comment,
            'count'=>$count
        ];
        return $data;
    }
    public function display(KintaiEntryDisplayRequest $request)
    {
        $dates=$request->input('date');
        $yyyymm=$request->input('yyyymm');
        $id=$request->input('id');
        $items=User::get();
        $holidays=Holiday::get();
        $attendance_heads=AttendanceHead::where('yyyymm',$yyyymm)->where('user_id',$id)->get();
        $values=AttendanceHead::where('user_id',$id)->get();
        $sendBack=false;
        $sendBack_yyyymm=[];
        $sendBack_comment=[];
        $count=0;
        $response=Http::get('https://holidays-jp.github.io/api/v1/date.json');
        $nationalHoliday=$response->json();
        $holidayName=[];
        $collection=collect($holidays);
        foreach($values as $value){
            if($value->status==='2'){
                $sendBack=true;
                $sendBack_yyyymm[]=$value->yyyymm;
                $sendBack_comment[]=$value->reject_comment;
                $count+=1;
            }
        }
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
        if(!$attendance_heads->isEmpty()){
            $attendance_details=AttendanceDetail::where('attendance_head_id',$attendance_heads[0]->id)->get();
            if($attendance_heads[0]->status==="1" || $attendance_heads[0]->status==="3"){
                $disabled=true;
            }
            else{
                $disabled=false;
            }
        }
        else{
            $attendance_details=[];
            $disabled=false;
        }
        $data=[
            'items'=>$items,
            'holidays'=>$holidays,
            'holidayName'=>$holidayName,
            'attendance_details'=>$attendance_details,
            'disabled'=>$disabled,
            'sendBack'=>$sendBack,
            'sendBack_yyyymm'=>$sendBack_yyyymm,
            'sendBack_comment'=>$sendBack_comment,
            'count'=>$count
        ];
        return $data;
    }
    public function updateSendBack(Request $request)
    {
        $id=$request->input('id');
        $values=AttendanceHead::where('user_id',$id)->get();
        $sendBack=false;
        $sendBack_yyyymm=[];
        $sendBack_comment=[];
        $count=0;
        foreach($values as $value){
            if($value->status==='2'){
                $sendBack=true;
                $sendBack_yyyymm[]=$value->yyyymm;
                $sendBack_comment[]=$value->reject_comment;
                $count+=1;
            }
        }
        $data=[
            'sendBack'=>$sendBack,
            'sendBack_yyyymm'=>$sendBack_yyyymm,
            'sendBack_comment'=>$sendBack_comment,
            'count'=>$count
        ];
        return $data;
    }
    public function save(Request $request){
        $id=$request->input('id');
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
        $save_id=AttendanceHead::where('user_id',$id)->where('yyyymm',$yyyymm)->get();
        if(!$save_id->isEmpty()){
            $attendance_head_id=$save_id[0]->id;
            $save_data=AttendanceDetail::where('attendance_head_id',$attendance_head_id)->get();
        }
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
        $save_id=AttendanceHead::where('user_id',$id)->where('yyyymm',$yyyymm)->get();
        $attendance_head_id=$save_id[0]->id;
        $save_data=AttendanceDetail::where('attendance_head_id',$attendance_head_id)->get();
        if($save_data->isEmpty()){
            DB::beginTransaction();
            try{
                for($i=0;$i<count($day);$i++){
                    if(is_null($start_time[$i])){
                        $start_time[$i]="00:00:00";
                    }
                    if(is_null($end_time[$i])){
                        $end_time[$i]="00:00:00";
                    }
                    if(is_null($rest_time[$i])){
                        $rest_time[$i]="00:00:00";
                    }
                    if(is_null($night_rest_time[$i])){
                        $night_rest_time[$i]="00:00:00";
                    }
                    if(is_null($work_time[$i])){
                        $work_time[$i]="00:00:00";
                    }
                    if(is_null($over_time[$i])){
                        $over_time[$i]="00:00:00";
                    }
                    if(is_null($remarks[$i])){
                        $remarks[$i]="";
                    }
                    $attendance_details=new AttendanceDetail();
                    $attendance_details->fill([
                        'attendance_head_id'=>$attendance_head_id,
                        'day'=>$day[$i],
                        'kbn'=>$kbn[$i],
                        'start_time'=>$start_time[$i],
                        'end_time'=>$end_time[$i],
                        'rest_time'=>$rest_time[$i],
                        'night_rest_time'=>$night_rest_time[$i],
                        'work_time'=>$work_time[$i],
                        'over_time'=>$over_time[$i],
                        'remarks'=>$remarks[$i]
                    ]);
                    $attendance_details->save();
                    DB::commit();
                }
                $updateAlert=true;
                $data=[
                    'updateAlert'=>$updateAlert
                ];
                return $data;
            }
            catch(\Exception $ex){
                DB::rollBack();
            }
        }
        else{
            DB::beginTransaction();
            try{
                for($i=0;$i<count($day);$i++){
                    if(is_null($start_time[$i])){
                        $start_time[$i]="00:00:00";
                    }
                    if(is_null($end_time[$i])){
                        $end_time[$i]="00:00:00";
                    }
                    if(is_null($rest_time[$i])){
                        $rest_time[$i]="00:00:00";
                    }
                    if(is_null($night_rest_time[$i])){
                        $night_rest_time[$i]="00:00:00";
                    }
                    if(is_null($work_time[$i])){
                        $work_time[$i]="00:00:00";
                    }
                    if(is_null($over_time[$i])){
                        $over_time[$i]="00:00:00";
                    }
                    if(is_null($remarks[$i])){
                        $remarks[$i]="";
                    }
                    $attendance_details=AttendanceDetail::find($save_data[$i]->id);
                    $attendance_details->fill([
                        'kbn'=>$kbn[$i],
                        'start_time'=>$start_time[$i],
                        'end_time'=>$end_time[$i],
                        'rest_time'=>$rest_time[$i],
                        'night_rest_time'=>$night_rest_time[$i],
                        'work_time'=>$work_time[$i],
                        'over_time'=>$over_time[$i],
                        'remarks'=>$remarks[$i]
                    ]);
                    $attendance_details->save();
                    DB::commit();
                }
                $updateAlert=true;
                $data=[
                    'updateAlert'=>$updateAlert
                ];
                return $data;
            }
            catch(\Exception $ex){
                DB::rollBack();
            }
        }
    }
    public function app(Request $request){
        $id=$request->input('id');
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
        $app_id=AttendanceHead::where('user_id',$id)->where('yyyymm',$yyyymm)->get();
        if(!$app_id->isEmpty()){
            $attendance_head_id=$app_id[0]->id;
            $app_data=AttendanceDetail::where('attendance_head_id',$attendance_head_id)->get();
        }
        if($app_id->isEmpty()){
            DB::beginTransaction();
            try{
                $attendance_heads=new AttendanceHead();
                $attendance_heads->fill([
                    'user_id'=>$id,
                    'yyyymm'=>$yyyymm,
                    'status'=>1,
                    'reject_comment'=>null
                ]);
                $attendance_heads->save();
                DB::commit();
            }
            catch(\Exception $ex){
                DB::rollBack();
            }
        }
        else{
            DB::beginTransaction();
            try{
                $attendance_heads=AttendanceHead::find($app_id[0]->id);
                $attendance_heads->fill([
                    'user_id'=>$id,
                    'yyyymm'=>$yyyymm,
                    'status'=>1,
                    'reject_comment'=>null
                ]);
                $attendance_heads->save();
                DB::commit();
            }
            catch(\Exception $ex){
                DB::rollBack();
            }
        }
        $app_id=AttendanceHead::where('user_id',$id)->where('yyyymm',$yyyymm)->get();
        $attendance_head_id=$app_id[0]->id;
        $save_data=AttendanceDetail::where('attendance_head_id',$attendance_head_id)->get();
        if($save_data->isEmpty()){
            DB::beginTransaction();
            try{
                for($i=0;$i<count($day);$i++){
                    if(is_null($start_time[$i])){
                        $start_time[$i]="00:00:00";
                    }
                    if(is_null($end_time[$i])){
                        $end_time[$i]="00:00:00";
                    }
                    if(is_null($rest_time[$i])){
                        $rest_time[$i]="00:00:00";
                    }
                    if(is_null($night_rest_time[$i])){
                        $night_rest_time[$i]="00:00:00";
                    }
                    if(is_null($work_time[$i])){
                        $work_time[$i]="00:00:00";
                    }
                    if(is_null($over_time[$i])){
                        $over_time[$i]="00:00:00";
                    }
                    if(is_null($remarks[$i])){
                        $remarks[$i]="";
                    }
                    $attendance_details=new AttendanceDetail();
                    $attendance_details->fill([
                        'attendance_head_id'=>$attendance_head_id,
                        'day'=>$day[$i],
                        'kbn'=>$kbn[$i],
                        'start_time'=>$start_time[$i],
                        'end_time'=>$end_time[$i],
                        'rest_time'=>$rest_time[$i],
                        'night_rest_time'=>$night_rest_time[$i],
                        'work_time'=>$work_time[$i],
                        'over_time'=>$over_time[$i],
                        'remarks'=>$remarks[$i]
                    ]);
                    $attendance_details->save();
                    DB::commit();
                }
                $appAlert=true;
                $data=[
                    'appAlert'=>$appAlert
                ];
                return $data;
            }
            catch(\Exception $ex){
                DB::rollBack();
            }
        }
        else{
            DB::beginTransaction();
            try{
                for($i=0;$i<count($day);$i++){
                    if(is_null($start_time[$i])){
                        $start_time[$i]="00:00:00";
                    }
                    if(is_null($end_time[$i])){
                        $end_time[$i]="00:00:00";
                    }
                    if(is_null($rest_time[$i])){
                        $rest_time[$i]="00:00:00";
                    }
                    if(is_null($night_rest_time[$i])){
                        $night_rest_time[$i]="00:00:00";
                    }
                    if(is_null($work_time[$i])){
                        $work_time[$i]="00:00:00";
                    }
                    if(is_null($over_time[$i])){
                        $over_time[$i]="00:00:00";
                    }
                    if(is_null($remarks[$i])){
                        $remarks[$i]="";
                    }
                    $attendance_details=AttendanceDetail::find($app_data[$i]->id);
                    $attendance_details->fill([
                        'kbn'=>$kbn[$i],
                        'start_time'=>$start_time[$i],
                        'end_time'=>$end_time[$i],
                        'rest_time'=>$rest_time[$i],
                        'night_rest_time'=>$night_rest_time[$i],
                        'work_time'=>$work_time[$i],
                        'over_time'=>$over_time[$i],
                        'remarks'=>$remarks[$i]
                    ]);
                    $attendance_details->save();
                    DB::commit();
                }
                $appAlert=true;
                $disabled=true;
                $data=[
                    'appAlert'=>$appAlert,
                    'disabled'=>$disabled
                ];
                return $data;
            }
            catch(\Exception $ex){
                DB::rollBack();
            }
        }
    }
}
