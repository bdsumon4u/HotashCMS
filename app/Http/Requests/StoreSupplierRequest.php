<?php

namespace App\Http\Requests;

use App\Models\Supplier;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;

class StoreSupplierRequest extends FormRequest
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
            'name' => 'required|max:35',
            'email' => 'required|email|max:255|unique:suppliers',
            'phone' => 'required|max:25|unique:suppliers',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        $supplier = Supplier::query()->select('id', 'phone', 'email')->firstWhere(function ($query) {
            $query->where('phone', $this->request->get('phone'))->orWhere('email', $this->request->get('email'));
        });

        if ($supplier) {
            $validator->messages()->add('supplier', $supplier->getKey());
            foreach (['phone', 'email'] as $column) {
                if ($supplier->$column === $this->request->get($column)) {
                    $validator->messages()->add('supplier', "There exist a supplier with the same {$column}.");
                }
            }
        }

        throw new ValidationException($validator);
    }
}
