<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOpdLetterheadRequest extends FormRequest
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
            'patient_id' => 'required|integer|exists:patients,id',
            'appointment_id' => 'required|integer|exists:advance_booking,id',
            'bp' => 'nullable|string',
            'pulse' => 'nullable|string',
            'spo2' => 'nullable|string',
            'temp' => 'nullable|string',
            'rs' => 'nullable|string',
            'cvs' => 'nullable|string',
            'diagnosis' => 'nullable|string', // Add validation for diagnosis
            'report' => 'nullable|string',
            'addition' => 'nullable|string',
            'complaint' => 'nullable|string',
            'past_history' => 'nullable|string',
            'note' => 'nullable|string',
            'next_date' => 'nullable|date',
            'medicines' => 'required|array',
            'medicines.*.quantity' => 'required|numeric|min:1',
            'medicines.*.frequency' => 'required|string',

        ];
    }
}
