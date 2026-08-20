<?php

namespace App\Rules;

use Closure;
use App\Models\AttendanceHead;
use Illuminate\Support\Facades\DB;

use Illuminate\Contracts\Validation\ValidationRule;

class kintaiMasterSendBackRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $checks=array_map('intval', explode(',', $value));
        $employee="";
        foreach($checks as $check){
            $attendance_heads=AttendanceHead::where('id',$check)->get();
            if($attendance_heads[0]->status!=='2'){
                $employee.="<br>年月:{$attendance_heads[0]->yyyymm},社員番号:{$attendance_heads[0]->user->user_no},社員名:{$attendance_heads[0]->user->name}";
            }
        }
        if($employee!==""){
            $fail("「申請中」以外が含まれています。{$employee}");
        }
    }
}
