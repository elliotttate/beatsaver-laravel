<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewTokenRequest extends FormRequest
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
            'delete' => 'required_unless:new,0,1',
            'new'    => 'required_without:delete',
        ];
    }
}
