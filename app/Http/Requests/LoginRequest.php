<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Rules\LoginRule;
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
            'loginEmail'=>'required|exists:users,email',
            'loginPassword'=>['required',new LoginRule]
        ];
    }
    public function messages()
    {
        return[
            'loginEmail.exists'=>'メールアドレスまたはパスワードが間違っています',
            'loginEmail.required'=>'メールアドレスまたはパスワードが間違っています',
            'loginPassword.exists'=>'メールアドレスまたはパスワードが間違っています',
            'loginEmail.numeric'=>'メールアドレスまたはパスワードが間違っています',
            'loginPassword.required'=>'メールアドレスまたはパスワードが間違っています'
        ];
    }
}
