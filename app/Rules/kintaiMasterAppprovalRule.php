<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use App\Models\AttendanceHead;

class kintaiMasterAppprovalRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $values, Closure $fail): void
    {
        foreach($values as $value){
            $AttendanceHeads=AttendanceHead::where('id',$value)->get();
            $status=$AttendanceHeads[0]->status;
            if($status!=='1'){

            }
        }
    }
}
