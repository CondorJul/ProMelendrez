<?php

namespace App\Http\Requests\business;

use Illuminate\Foundation\Http\FormRequest;

class updBussDataRequest extends FormRequest
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
            'business.bussKind' => 'required',
            'business.bussName' => 'required',
            'business.bussRUC' => 'required',
            'business.bussAddress' => '',
            'business.bussFileKind' => 'required',
            'business.bussFileNumber' => 'required',
            'business.bussState' => 'required',
            'business.bussTel' => '',
            'business.bussTel2' => '',
            'business.bussTel3' => ''
        ];
    }
}
