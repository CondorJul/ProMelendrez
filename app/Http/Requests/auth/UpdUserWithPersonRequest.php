<?php

namespace App\Http\Requests\auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdUserWithPersonRequest extends FormRequest
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
            'email' => 'required|email',
            'person.perKindDoc'=> 'required', 
            'person.perNumberDoc'=>'required',
            'person.perName'=>'required',
            'person.perTel'=>'required',
        ];
    }
}
