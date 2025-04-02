<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UserLoginRequest
 *
 * Handles the validation of user login data.
 * It ensures that the provided email exists in the `users` table and is in a valid format.
 * The password is required and should be a string.
 *
 * @package App\Http\Requests\Auth
 */
class UserLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * This method determines whether the user is authorized to perform the login action.
     * By default, it returns true, allowing any user to attempt login as long as they pass validation.
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
     * This method defines the validation rules that will be applied to the login request data.
     * The `email` field is required, must be a valid email format, and must exist in the `users` table.
     * The `password` field is required and must be a string.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string> The validation rules.
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string'
        ];
    }
}
