<?php

namespace App\Http\Requests\period;

use Illuminate\Foundation\Http\FormRequest;

class AddPeriodRequest extends FormRequest
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
            'prdsNameShort'=>'required|unique:periods,prdsNameShort', 
            'prdsState'=>'required',
            
        ];
    }
}
