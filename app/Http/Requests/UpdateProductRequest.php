<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;

class UpdateProductRequest extends StoreProductRequest
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
        return parent::rules();
    }

    protected function variableRules(): array
    {
        $product = $this->route('product');

        $commons = ['max:25', Rule::unique('products')->ignore($product), Rule::unique('variations')->whereNot('product_id', $product->id)];

        return array_merge(parent::variableRules(), parent::variationLess() ? [
            'sku' => ['required', ...$commons],
            'barcode' => ['required', ...$commons],
        ]: [
            'variations.*.sku' => ['required_if:variations.*.enabled,true', /*'distinct',*/ ...$commons],
            'variations.*.barcode' => ['required_if:variations.*.enabled,true', /*'distinct',*/ ...$commons],
        ]);
    }
}

