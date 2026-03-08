<?php

namespace App\Http\Requests\AdminUser;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'username' => [
                'sometimes',
                'required',
                'max:255',
                'min:4',
                Rule::unique('admin_users')->ignore($this->route('admin_user')),
            ],
            'email' => [
                'sometimes',
                'required',
                'max:255',
                'min:4',
                'email',
                Rule::unique('admin_users')->ignore($this->route('admin_user')),
            ],
        ];
    }
}
