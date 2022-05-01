<?php

namespace App\Http\Requests\videos;

use Illuminate\Foundation\Http\FormRequest;

class updVideosRequest extends FormRequest
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
            "vidName" => "required",
            "vidLink" => "required",
            "vidState" => "required"
        ];
    }
}
