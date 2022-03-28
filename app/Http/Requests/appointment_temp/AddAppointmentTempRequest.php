<?php

namespace App\Http\Requests\appointment_temp;

use Illuminate\Foundation\Http\FormRequest;

class AddAppointmentTempRequest extends FormRequest
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
            'catId'=>'required'
        ];
    }
}
