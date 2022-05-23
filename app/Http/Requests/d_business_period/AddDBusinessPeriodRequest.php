<?php

namespace App\Http\Requests\d_business_period;

use Illuminate\Foundation\Http\FormRequest;

class AddDBusinessPeriodRequest extends FormRequest
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
            'prdsId'=>'required',
            'bussId'=>'required',
        ];
    }
}
