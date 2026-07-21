<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if($this->path()=='login/login'){
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
            'loginID'=>'required|numeric|exists:users,id',
            'loginPassword'=>'required|exists:users,password'
        ];
    }
    public function messages()
    {
        return[
            'loginID.exists'=>'IDまたはパスワードが間違っています',
            'loginID.required'=>'IDまたはパスワードが間違っています',
            'loginPassword.exists'=>'IDまたはパスワードが間違っています',
            'loginID.numeric'=>'IDまたはパスワードが間違っています',
            'loginPassword.required'=>'IDまたはパスワードが間違っています'
        ];
    }
}
