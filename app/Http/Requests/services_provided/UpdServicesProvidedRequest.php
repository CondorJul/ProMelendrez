<?php

namespace App\Http\Requests\services_provided;

use Illuminate\Foundation\Http\FormRequest;

class UpdServicesProvidedRequest extends FormRequest
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
            'spId'=>'required',
            'dbpId' => 'required',
            'svId' => 'required',
            'ppayId' => 'required',
            'spCost' => 'required',
            'spComment' => '',
        ];
    }
}
