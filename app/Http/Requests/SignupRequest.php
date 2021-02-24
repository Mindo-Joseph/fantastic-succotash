<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest
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
            'name' => 'required|min:3|max:50',
            'email' => 'required|email|max:50||unique:users',
            'password' => 'required|string|min:6|max:50',
            'phone_number' => 'required|numeric|digits:10|unique:users',
            'device_type' => 'required|string',
            'device_token' => 'required|string',
            'country_id' => 'required|string',
        ];
    }
}