<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\kintaiMasterApprovalRule;

class kintaiMasterApprovalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if($this->path()=='kintai_master/approval'){
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'check'=>['required',new kintaiMasterApprovalRule()]
        ];
    }
    public function messages()
    {
        return[
            'check.required'=>'勤怠が選択されていません。'
        ];
    }
}
