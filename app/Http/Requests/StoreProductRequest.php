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
        $rules = array_merge($this->hasVariation() ? Arr::dot([
            'variations.*' => [
                'sku' => 'required_if:variations.*.enabled,true|max:25|unique:products',
                'barcode' => 'required_if:variations.*.enabled,true|max:25|unique:products|unique:variations',
                'regular_price' => 'required_if:variations.*.enabled,true|numeric|min:0',
                'discount_amount' => 'required_if:variations.*.enabled,true|numeric|min:0',
                'discount_type' => 'required_if:variations.*.enabled,true|in:fixed,percent',
                'sale_price' => 'required_if:variations.*.enabled,true|numeric|min:0',
                'schedule' => 'sometimes|boolean',
                'sale_start_date' => 'nullable|date',
                'sale_end_date' => 'nullable|date',
                'images' => 'nullable|array',
            ],
        ]) : [
            'sku' => ['required', 'max:25'],
            'barcode' => ['required', 'max:25'],
            'regular_price' => ['required', 'numeric'],
            'discount_amount' => ['required', 'numeric'],
            'discount_type' => ['required', Rule::in(['fixed', 'percent'])],
            'sale_price' => ['required', 'numeric'],
            'schedule' => ['required', 'boolean'],
            'sale_start_date' => ['nullable', 'date'],
            'sale_end_date' => ['nullable', 'date'],
        ], [
            'images' => 'required|array',
            'name' => 'required|max:192',
            'slug' => 'required|max:192',
            'description' => 'required',
            'type' => ['required', Rule::in(['standard', 'digital', 'service'])],
            'brand_id' => 'required|integer',
            'categories' => 'nullable|array',
            'attributes' => 'nullable|array',
            'variations' => 'nullable|array',
        ]);
//        dd($rules);
        return $rules;
    }

    public function messages()
    {
        return Arr::dot([
            'brand_id' => [
                'required' => 'The brand field is required.',
                'integer' => 'The brand id must be an integer.',
            ],
            'variations.*' => [
                'sku' => [
                    'required_if' => 'This field is required.',
                    'max' => 'This field must not be greater than :max.',
                    'unique' => 'This value has already been taken.',
                ],
                'barcode' => [
                    'required_if' => 'This field is required.',
                    'max' => 'This field must not be greater than :max.',
                    'unique' => 'This value has already been taken.',
                ],
                'regular_price' => [
                    'required_if' => 'This field is required.',
                    'numeric' => 'This field must be a number.',
                    'min' => 'This field must not be smaller than :min.',
                ],
                'discount_amount' => [
                    'required_if' => 'This field is required.',
                    'numeric' => 'This field must be an integer.',
                    'min' => 'This field must not be smaller than :min.',
                ],
                'discount_type' => [
                    'required_if' => 'This field is required.',
                ],
            ],
        ]);
    }

    private function hasVariation(): bool
    {
        return $this->collect('variations')->isNotEmpty();
    }
}
