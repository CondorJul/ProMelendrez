<?php

namespace App\Http\Requests\business;

use Illuminate\Foundation\Http\FormRequest;

class updAfiDataRequest extends FormRequest
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
            'afiliation.bussSunatUser' => '',
            'afiliation.bussSunatPass' => '',
            'afiliation.bussCodeSend' => '',
            'afiliation.bussCodeRNP' => '',
            'afiliation.bussAfpUser' => '',
            'afiliation.bussAfpPass' => '',
            'afiliation.bussSimpleCode' => '',
            'afiliation.bussDetractionsPass' => '',
            'afiliation.bussSisClave' => ''
        ];
    }
}
