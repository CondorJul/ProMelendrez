<?php

namespace App\Http\Requests\business;

use Illuminate\Foundation\Http\FormRequest;

class updAdiDataRequest extends FormRequest
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
            'aditional.bussDateMembership' => 'required',
            'aditional.bussDateStartedAct' => 'required',
            'aditional.bussRegime' => '',
            'aditional.bussKindBookAcc' => '',
            'aditional.bussEmail' => '',
            'aditional.bussObservation' => ''
        ];
    }
}
