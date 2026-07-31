<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KintaiEntryDisplayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if($this->path()=="api/kintai_entry_api/display"){
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
            'month'=>'required'
        ];
    }
    public function messages()
    {
        return[
            'month.required'=>'年月が選択されていません'
        ];
    }
}
