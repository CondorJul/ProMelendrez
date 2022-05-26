<?php

namespace App\Http\Requests\md_services;

use Illuminate\Foundation\Http\FormRequest;

class AddServicesRequest extends FormRequest
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
            'svName' => 'required',
            'svState' => 'required'
        ];
    }
}
