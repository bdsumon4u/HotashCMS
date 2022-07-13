<?php

namespace App\Http\Requests;

use App\Enums\PurchaseStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rules\Enum;

class StorePurchaseRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        $this->merge(['purchased_at' => now()]);
        $keys = ['discount_amount', 'tax_amount', 'service_charge'];
        $products = $this->get('products', []);
        $this->merge(['subtotal' => array_reduce($products, fn ($acc, $data) => $data['total'])]);

        foreach ($keys as $key) {
            if (is_null($this->get($key))) {
                $this->merge([$key => 0]);
            }
        }

        # For Individual Product
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
//        return [];
        return [
            'purchased_at' => 'required|date',
            'branch_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'status' => ['required', new Enum(PurchaseStatus::class)],
            'products' => 'required|array',

            'products.*.id' => 'required|integer',
            'products.*.quantity' => 'required|numeric|gte:1',
            'products.*.price' => 'required|numeric|gte:0',
            'products.*.discount_amount' => 'required|numeric|gte:0',
            'products.*.discount_type' => 'required|in:flat,percent',
            'products.*.discount' => 'required|numeric|gte:0',
            'products.*.tax_amount' => 'required|numeric|gte:0',
            'products.*.tax_type' => 'required|in:exclusive,inclusive',
            'products.*.tax' => 'required|numeric|gte:0',
            'products.*.net_price' => 'required|numeric',
            'products.*.total' => 'required|numeric',
            'products.*.unit_id' => 'required|integer',

            'subtotal' => 'required|numeric|gte:0',
            'discount_amount' => 'required|numeric|gte:0',
            'discount_type' => 'required|in:flat,percent',
            'tax_amount' => 'required|numeric|gte:0',
            'service_charge' => 'required|numeric|gte:0',
            'note' => 'nullable',
            'total' => 'required|numeric|gte:0',
        ];
    }

    public function messages(): array
    {
        return Arr::dot([
            'products.*' => [
                '*' => [
                    'required_if' => 'This field is required.',
                    'numeric' => 'This field must be an integer.',
                    'min' => 'This field must not be smaller than :min.',
                    'gte' => 'This field must be greater than or equal to :value.',
                    'max' => 'This field must not be greater than :max.',
                    'distinct' => 'The field has a duplicate value.',
                    'unique' => 'This value has already been taken.',
                ],
            ],
        ]);
    }
}
