<?php

namespace App\Http\Requests\AdminUser;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminUserRequest extends FormRequest
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
            'name' => 'nullable|max:255',
            'username' => 'sometimes|required|unique:admin_users|max:255|min:4',
            'email' => 'sometimes|required|unique:admin_users|max:255|min:4|email',
            'password' => 'sometimes|required|max:255|min:4|confirmed'
        ];
    }
}
