<?php

namespace App\Http\Requests\business;

use Illuminate\Foundation\Http\FormRequest;

class AddBusinessWithPersonRequest extends FormRequest
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
            'business.bussRUC' => 'required|unique:bussines,bussRUC',
            'business.bussAddress' => '',
            'business.bussFileKind' => 'required',
            'business.bussFileNumber' => 'required',
            'business.bussDateStartedAct' => '',
            'business.bussDateMembership' => '',
            'business.tellId' => 'required',
            'person.perKindDoc' => 'required',
            'person.perName' => 'required',
            'person.perNumberDoc' => 'required|unique:person,perNumberDoc',
            'person.perTel' => ''
        ];
    }
}
