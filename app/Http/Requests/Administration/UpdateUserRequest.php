<?php

namespace App\Http\Requests\Administration;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name' =>'required|string|max:16|alpha_num',
            'email' =>'required|string|email',
            'password' => 'nullable|string|min:8',
            'admin' => 'nullable|boolean',
            'banned' => 'nullable|boolean'
        ];
    }
}
