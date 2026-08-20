<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\kintaiMasterSendBackRule;

class kintaiMasterSendBackRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if($this->path()=='kintai_master/sendBack'){
            return true;
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'sendBackCheck'=>[new kintaiMasterSendBackRule()],
            'reject_comment'=>'required'
        ];
    }
    public function messages()
    {
        return[
            'reject_comment.required'=>'差戻理由を記入してください'
        ];
    }
}
