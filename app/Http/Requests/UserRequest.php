<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'user.email' => 'required|email|max:100',
            'user.name' => 'required|string|max:100',
            'user.kana' => 'string|max:100',
            'user.type' => 'integer|between:0,3',
            'group' => 'array',
            'group.*' => 'integer|exists:groups,id',

        ];
    }
}
