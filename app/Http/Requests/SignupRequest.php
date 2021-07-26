<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignupRequest extends FormRequest{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(){
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(){
        return [
            'name' => 'required|min:3|max:50',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6|max:50',
            'phone_number' => 'required|string|min:10|max:15|unique:users',
            'device_type' => 'required|string',
            'device_token' => 'required|string',
            'refferal_code' => 'nullable|exists:user_refferals,refferal_code',
        ];
    }
    public function messages(){
        return [
            "name.required" => __('The name field is required.'),
            "email.required" => __('The email field is required.'),
            "email.unique" => __('The email has already been taken.'),
            "phone_number.unique" => __('The phone number has already been taken.'),
            "name.min" => __('The name must be at least 3 characters.'),
            "password.required" => __("The password field is required."),
            "name.max" => __('The name may not be greater than 50 characters.'),
            "phone_number.required" => __('The phone number field is required.'),
        ];
    }
}
