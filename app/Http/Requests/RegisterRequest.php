<?php

namespace App\Http\Requests;

class RegisterRequest extends BaseApiRequest
{
    /**
     * Everyone can register — no auth checks needed
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for registration
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'in:admin,manager,user',
        ];
    }

    /**
     * Custom error messages (optional)
     */
    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already registered.',
            'password.confirmed' => 'Passwords must match.',
            'role.in' => 'Role must be admin, manager, or user.',
        ];
    }
}
