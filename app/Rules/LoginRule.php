<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class LoginRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */

    /* 入力されたメールアドレスを取得 */
    public function __construct(string $email='loginEmail')
    {
        $this->email=$email;
    }

    /* 入力されたメールアドレスのユーザのパスワードと入力されたパスワードが一致しているかチェック */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $this->inputEmail=request()->input($this->email);
        $user=DB::table('users')->where('email',$this->inputEmail)->first();
        if(!is_null($this->inputEmail) && $user->password!==$value){
            $fail('メールアドレスまたはパスワードが間違っています');
        }
    }
}
