<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HolidayAddRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if($this->path()=='holiday/add'){
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
            'newDate'=>'required',
            'newName'=>'required'
        ];
    }
    public function messages(){
        return[
            'newDate.required'=>'日付は必須項目です',
            'newName.required'=>'祝日名は必須項目です'
        ];
    }
}
