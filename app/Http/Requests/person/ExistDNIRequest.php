<?php

namespace App\Http\Requests\person;

use Illuminate\Foundation\Http\FormRequest;

class ExistDNIRequest extends FormRequest
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
            'perNumberDoc' => 'required'
        ];
    }
}
