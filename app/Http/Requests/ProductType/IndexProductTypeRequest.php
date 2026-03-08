<?php

namespace App\Http\Requests\ProductType;

use Illuminate\Foundation\Http\FormRequest;

class IndexProductTypeRequest extends FormRequest
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
            'search' => 'nullable|max:255',
            'page' => 'nullable|numeric|min:1',
            'per_page' => 'nullable|numeric|min:1',
            'sort_by' => 'nullable|in:id,name',
            'sort_order' => 'nullable|in:asc,desc',
        ];
    }
}
