<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
Use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class DispatchOrderStatusUpdateRequest extends FormRequest{
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
            'order_id' => 'required:exists:user_addresses,id',
            'dispatcher_status_option_id' => 'required:exists:user_addresses,id',
            'vendor_id' => 'required:exists:user_addresses,id',
        ];
    }
    public function messages(){
        return [
            'order_id.required' => 'Invalid Order',
            'order_id.exists' => 'Invalid Order',
            'dispatcher_status_option_id.required' => 'Invalid status option',
            'dispatcher_status_option_id.exists' =>  'Invalid status option',
            'vendor_id.required' => 'Invalid Vendor',
            'vendor_id.exists' => 'Invalid Vendor'
        ];
    }

    /**
     * [failedValidation [Overriding the event validator for custom error response]]
     * @param  Validator $validator [description]
     * @return [object][object of various validation errors]
     */
    public function failedValidation(Validator $validator)
    {
        $data_error = [];
        $error = $validator->errors()->all(); #if validation fail print error messages
        foreach ($error as $key => $errors):
            $data_error['status'] = 400;
            $data_error['message'] = $errors;
        endforeach;
        //write your bussiness logic here otherwise it will give same old JSON response
        throw new HttpResponseException(response()->json($data_error));

    }
}
