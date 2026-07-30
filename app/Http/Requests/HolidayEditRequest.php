<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HolidayEditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if($this->path()=='holiday/edit'){
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
            'updateDate'=>'required',
            'updateName'=>'required'
        ];
    }
    public function messages()
    {
        return [
            'updateDate.required'=>'日付は必須項目です',
            'updateName.required'=>'祝日名は必須項目です'
        ];
    }
}
