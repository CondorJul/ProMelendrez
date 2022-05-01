<?php

namespace App\Http\Requests\auth;

use Illuminate\Foundation\Http\FormRequest;

class ChangePassworWithAuthdRequest extends FormRequest
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
            'passwordOld'=>'required',
            'passwordNew'=>'required_with:passwordNewRepet|same:passwordNewRepet',
            'passwordNewRepet'=>'required'
        ];
    }
}
