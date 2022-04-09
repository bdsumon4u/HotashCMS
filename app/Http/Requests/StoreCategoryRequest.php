<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', Rule::unique('categories'), 'max:35'],
            'slug' => ['required', Rule::unique('categories'), 'max:35'],
            'image' => ['sometimes', 'nullable', $this->hasFile('image') ? 'image' : 'string'],
        ];
    }
}
