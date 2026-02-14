<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
           
            'appointment_id' => 'required|exists:advance_booking,id',
          
           
            'amount' => 'required|numeric',
            'payment_type' => 'required|string|max:50',
            'gender' => 'required|string|in:Male,Female,Other',
            'age' => 'required|integer|min:1|max:120',
            // 'phone' => 'required|numeric|min:1000000000|max:9999999999', // For 10-digit numbers
            'charges' => 'required|array|min:1',
            'charges.*' => 'required|exists:charges,id',
            'referred_by' => 'nullable|exists:doctors,id',
        ];
    }


     /**
     * Get the custom validation messages.
     *
     * @return array
     */
    public function messages()
    {
        return [

            'amount.required' => 'Amount is required.',
            'age.required' => 'Age is required.',
            'gender.required' => 'Gender is required.',
            'address.required' => 'Address is required.',
            'phone.required' => 'Allowed only 10 digit number is required.',
            
        ];
    }
}
