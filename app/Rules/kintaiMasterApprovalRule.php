<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use App\Models\AttendanceHead;

class kintaiMasterApprovalRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /* "4,7"を[4,7]に変更する */
        $checks=array_map('intval', explode(',', $value));
        $employee="";
        if(is_array($checks)){
            foreach($checks as $check){
                $AttendanceHeads=AttendanceHead::where('id',$check)->get();
                $status=$AttendanceHeads[0]->status;

                if($status!=='1'){
                    $employee.="年月:{$AttendanceHeads[0]->yyyymm},社員番号:{$AttendanceHeads[0]->user->user_no},社員名:{$AttendanceHeads[0]->user->name}";
                }
            }
        }
        if($employee!==""){
            $fail("「申請中」以外が含まれています。{$employee}");
        }
    }
}
