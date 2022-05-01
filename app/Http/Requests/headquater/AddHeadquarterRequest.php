<?php

namespace App\Http\Requests\headquater;

use Illuminate\Foundation\Http\FormRequest;

class AddHeadquarterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'hqName'=>'required|unique:headquarter,hqName',
        ];
    }
}
