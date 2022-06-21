<?php

namespace App\Http\Requests\copy_to_clipboard;

use Illuminate\Foundation\Http\FormRequest;

class AddCopyToClipboardRequest extends FormRequest
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
            'copiedWord'=>'required',
            'keyName'=>'required'
        ];
    }
}
