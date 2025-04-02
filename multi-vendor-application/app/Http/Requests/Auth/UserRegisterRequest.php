<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserRegisterRequest
 *
 * Handles the validation of user registration data.
 * It ensures that the provided email is valid and unique, and that the password is required.
 */
class UserRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * This method determines whether the user has permission to make this registration request.
     * By default, this returns true, allowing any user to register.
     *
     * @return bool True if the user is authorized to make the request, otherwise false.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * This method defines the validation rules that will be applied to the incoming registration data.
     * The `email` field is required, must be a valid email format, and must be unique in the `users` table.
     * The `password` field is required.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string> The validation rules.
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
        ];
    }

    /**
     * Get custom messages for validation errors.
     *
     * This method defines custom error messages for the validation rules.
     * If a validation rule fails, the corresponding custom message will be returned.
     *
     * @return array Custom error messages for the validation rules.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Email address is required',
            'email.email' => 'Please provide a valid email address',
            'email.unique' => 'This email is already registered',
            'password.required' => 'Password is required',
        ];
    }
}
