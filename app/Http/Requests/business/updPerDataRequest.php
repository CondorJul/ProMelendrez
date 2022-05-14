<?php

namespace App\Http\Requests\business;

use Illuminate\Foundation\Http\FormRequest;

class updPerDataRequest extends FormRequest
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
            'person.perKindDoc' => 'required',
            'person.perName' => 'required',
            'person.perNumberDoc' => 'required',
            'person.perAddress' => '',
            'person.perTel' => '',
            'person.perEmail' => ''
        ];
    }
}
