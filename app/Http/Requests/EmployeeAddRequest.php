<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;
use App\Rules\EmployeeAddRule;

class EmployeeAddRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if($this->path()=='api/employee_api/add'){
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
            'newNumber'=>'required',
            'newName'=>'required',
            'newDate'=>'required',
            'newRole_cd'=>'required',
            'newEmail'=>'required|unique:users,email',
            'newPassword'=>['required',new EmployeeAddRule()],
            'newCheckPassword'=>'required'
        ];
    }
    public function messages()
    {
        return[
            'newNumber.required'=>'社員番号が入力されていません',
            'newName.required'=>'社員名が入力されていません',
            'newDate.required'=>'入社日が入力されていません',
            'newRole_cd.required'=>'権限が入力されていません',
            'newEmail.required'=>'メールアドレスが入力されていません',
            'newEmail.unique'=>'入力されたメールアドレスは既に存在します',
            'newPassword.required'=>'パスワードが入力されていません',
            'newCheckPassword.required'=>'確認用パスワードが入力されていません'
        ];
    }
}
