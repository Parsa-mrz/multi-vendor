<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class UpdateProfileRequest
 *
 * This request is responsible for validating the incoming request
 * for updating the profile details. It contains validation rules for
 * the user's first name, last name, and phone number.
 *
 * @package App\Http\Requests\Profile
 */
class UpdateProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * This method is used to check if the currently authenticated user
     * is authorized to make the profile update request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;  // Return true to allow all users (this can be modified as needed for authorization)
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * This method defines the validation rules for the profile update request.
     * It includes rules for the first name, last name, and phone number.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255|unique:profiles,phone_number'
        ];
    }

    /**
     * Get custom validation messages.
     *
     * This method provides custom error messages for the validation rules
     * defined in the `rules()` method. These messages will be returned
     * if any of the validation rules fail.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'first_name.required' => 'The first name is required.',
            'first_name.string' => 'The first name must be a string.',
            'first_name.max' => 'The first name must not be longer than 255 characters.',
            'last_name.required' => 'The last name is required.',
            'last_name.string' => 'The last name must be a string.',
            'last_name.max' => 'The last name must not be longer than 255 characters.',
            'phone_number.required' => 'The phone number is required.',
            'phone_number.string' => 'The phone number must be a string.',
            'phone_number.max' => 'The phone number must not be longer than 255 characters.',
            'phone_number.unique' => 'The phone number is already taken. Please provide a different one.',
        ];
    }
}
