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

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'type_id.required' => __('messages.validation.type_id.required'),
            'type_id.exists' => __('messages.validation.type_id.exists'),
            'name.required' => __('messages.validation.name.required'),
            'name.string' => __('messages.validation.name.string'),
            'name.max' => __('messages.validation.name.max'),
            'gender.required' => __('messages.validation.gender.required'),
            'gender.in' => __('messages.validation.gender.in'),
            'breed_id.exists' => __('messages.validation.breed_id.exists'),
            'breed_clarification.in' => __('messages.validation.breed_clarification.in'),
            'breed_text.string' => __('messages.validation.breed_text.string'),
            'breed_text.max' => __('messages.validation.breed_text.max'),
            'knows_dob.required' => __('messages.validation.knows_dob.required'),
            'knows_dob.in' => __('messages.validation.knows_dob.in'),
            'approx_age_years.integer' => __('messages.validation.approx_age_years.integer'),
            'approx_age_years.min' => __('messages.validation.approx_age_years.min'),
            'approx_age_years.max' => __('messages.validation.approx_age_years.max'),
            'dob.date' => __('messages.validation.dob.date'),
            'dob.before_or_equal' => __('messages.validation.dob.before_or_equal'),
        ];
    }
}
