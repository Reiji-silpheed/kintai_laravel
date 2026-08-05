<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;
use App\Rules\EmployeeEditRule;

class EmployeeEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if($this->path()=='api/employee_api/edit'){
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
            'updateNumber'=>'required|numeric',
            'updateName'=>'required',
            'updateDate'=>'required',
            'updateRole_cd'=>'required',
            'updateEmail'=>'required|email|regex:/^[\x20-\x7E]+$/',
            'updatePassword'=>[new EmployeeEditRule()]
        ];
    }
    public function messages()
    {
        return[
            'updateNumber.required'=>'社員番号は必須項目です',
            'updateNumber.numeric'=>'社員番号は半角の数字で入力して下さい',
            'updateName.required'=>'社員名は必須項目です',
            'updateDate.required'=>'入社日は必須項目です',
            'updateRole_cd.required'=>'権限は必須項目です',
            'updateEmail.required'=>'メールアドレスは必須項目です',
            'updateEmail.email'=>'メールアドレス形式で入力してください',
            'updateEmail.regex'=>'メールアドレスは半角で入力してください',
            'updateEmail.email'=>'メールアドレス形式で入力してください',
            'updateEmail.regex'=>'メールアドレスは半角で入力してください'
        ];
    }
}
