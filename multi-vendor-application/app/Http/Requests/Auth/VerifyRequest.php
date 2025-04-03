<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class VerifyRequest
 *
 * Handles validation for OTP verification requests.
 */
class VerifyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool Always returns true, meaning all users can make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     *         The validation rules for verifying the OTP.
     */
    public function rules(): array
    {
        return [
            'type' => 'required|in:email',
            'value' => 'required|string|unique:users,email',
            'code' => 'required|integer|digits:4',
            'password' => 'required',
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * This method defines custom error messages for the validation rules.
     * If a validation rule fails, the corresponding custom message will be returned.
     *
     * @return array<string, string> Custom error messages for the validation rules.
     */
    public function messages(): array
    {
        return [
            'type.required' => 'Type field is required.',
            'type.in' => 'Please provide a valid type (only "email" is allowed).',
            'value.required' => 'Value field is required.',
            'value.string' => 'Value must be a string.',
            'value.unique' => 'This email is already registered.',
            'code.required' => 'OTP code is required.',
            'code.integer' => 'OTP code must be a numeric value.',
            'code.digits' => 'OTP code must be exactly 4 digits.',
            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a string.',
        ];
    }
}
