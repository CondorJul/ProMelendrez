<?php

namespace App\Http\Requests\payment_method;

use Illuminate\Foundation\Http\FormRequest;

class UpdPaymentMethodRequest extends FormRequest
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
            'paymthdsId'=>'required',
            'paymthdsName'=>'required', 
            'paymthdsState'=>'required', /*1=activo, 2=inactivo*/  
        ];
    }
}
