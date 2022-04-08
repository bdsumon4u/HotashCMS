<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateBrandRequest extends StoreBrandRequest
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
        $model = $this->route()->parameter('brand');

        return [
            'name' => ['required', Rule::unique('brands')->ignoreModel($model), 'max:35'],
            'slug' => ['required', Rule::unique('brands')->ignoreModel($model), 'max:35'],
            'image' => ['sometimes', 'nullable', $this->hasFile('image') ? 'image' : 'string'],
        ];
    }
}
