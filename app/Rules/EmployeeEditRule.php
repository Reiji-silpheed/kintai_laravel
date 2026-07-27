<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EmployeeEditRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function __construct(string $password='updatePassword',string $checkPassword='updateCheckPassword')
    {
        $this->password=$password;
        $this->checkPassword=$checkPassword;
    }
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $this->inputPassword=request()->input($this->password);
        $this->inputCheckPassword=request()->input($this->checkPassword);
        if($this->inputPassword!==""){
            if($this->inputPassword!==$this->inputCheckPassword){
                $fail('パスワードが一致しません');
            }
        }
    }
}
