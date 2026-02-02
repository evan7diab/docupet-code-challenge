<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PetSaveRequest extends FormRequest
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
            'type_id' => 'required|exists:types,id',
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'breed_id' => 'nullable|exists:breeds,id',
            'breed_clarification' => 'nullable|in:unknown,mix',
            'breed_text' => 'nullable|string|max:255',
            'knows_dob' => 'required|in:yes,no',
            'approx_age_years' => 'nullable|integer|min:1|max:20',
            'dob' => 'nullable|date|before_or_equal:today',
        ];
    }
}
