<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
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
//            'media' => 'required|array',
            'sku' => 'required|max:25',
            'name' => 'required|max:192',
            'slug' => 'required|max:192',
//            'description' => 'required',
//            'type' => ['required', Rule::in(['standard', 'digital', 'service'])],
            'brand_id' => 'required|integer',
            'categories' => 'nullable|array',
            'regular_price' => 'required|integer',
            'sale_price' => 'nullable|integer',
            'schedule' => 'required|boolean',
            'sale_start_date' => 'nullable|date',
            'sale_end_date' => 'nullable|date',
            'attributes' => 'nullable|array',
            'variations' => 'nullable|array',
        ];
    }

    public function messages()
    {
        return Arr::dot([
            'brand_id' => [
                'required' => 'The brand field is required.',
                'integer' => 'The brand id must be an integer.',
            ],
        ]);
    }
}
