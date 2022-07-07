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
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return array_merge($this->variableRules(), [
            'media' => 'required|array',
            'name' => 'required|max:192',
            'slug' => 'required|max:192',
            'description' => 'required',
            'type' => ['required', Rule::in(['standard', 'digital', 'service'])],
            'brand_id' => 'required|integer',
            'categories' => 'nullable|array',
            'attributes' => 'nullable|array',
            'variations' => 'nullable|array',
        ]);
    }

    public function attributes()
    {
        return [
            'brand_id' => 'brand',
        ];
    }

    public function messages(): array
    {
        return Arr::dot([
            'variations.*' => [
                '*' => [
                    'required_if' => 'This field is required.',
                    'numeric' => 'This field must be an integer.',
                    'min' => 'This field must not be smaller than :min.',
                    'max' => 'This field must not be greater than :max.',
                    'distinct' => 'The field has a duplicate value.',
                    'unique' => 'This value has already been taken.',
                ],
            ],
        ]);
    }

    protected function variableRules(): array
    {
        if ($this->variationLess()) {
            return [
                'sku' => ['required', 'max:25'],
                'barcode' => ['required', 'max:25'],
                'regular_price' => ['required', 'numeric'],
                'discount_amount' => ['required', 'numeric'],
                'discount_type' => ['required', Rule::in(['fixed', 'percent'])],
                'sale_price' => ['required', 'numeric'],
                'schedule' => ['required', 'boolean'],
                'sale_start_date' => ['nullable', 'date'],
                'sale_end_date' => ['nullable', 'date'],
            ];
        }

        return [
            'variations.*.enabled' => ['sometimes', 'boolean'],
            'variations.*.type' => ['required_if:variations.*.enabled,true', Rule::in(['standard', 'digital', 'service'])],
            'variations.*.sku' => ['required_if:variations.*.enabled,true', 'max:25', /*'distinct',*/ 'unique:products', 'unique:variations'],
            'variations.*.barcode' => ['required_if:variations.*.enabled,true', 'max:25', /*'distinct',*/ 'unique:products', 'unique:variations'],
            'variations.*.regular_price' => ['required_if:variations.*.enabled,true', 'numeric', 'min:0'],
            'variations.*.discount_amount' => ['required_if:variations.*.enabled,true', 'numeric', 'min:0'],
            'variations.*.discount_type' => ['required_if:variations.*.enabled,true', Rule::in(['flat', 'percent'])],
            'variations.*.sale_price' => ['required_if:variations.*.enabled,true', 'numeric', 'min:0'],
            'variations.*.schedule' => ['sometimes', 'boolean'],
            'variations.*.sale_start_date' => ['nullable', 'date'],
            'variations.*.sale_end_date' => ['nullable', 'date'],
            'variations.*.media' => ['nullable', 'array'],
        ];
    }

    protected function variationLess(): bool
    {
        return $this->collect('variations')->isEmpty();
    }
}
