<?php

namespace App\Http\Requests\cards;

use Illuminate\Foundation\Http\FormRequest;

class updCardsRequest extends FormRequest
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
            "cardName" => "required",
            "cardPhrases" => "required",
            "cardState" => "required"
        ];
    }
}
