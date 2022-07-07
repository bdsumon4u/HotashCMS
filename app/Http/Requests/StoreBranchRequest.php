<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBranchRequest extends FormRequest
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
            'name' => 'required|max:35|unique:branches',
            'country' => 'required|max:55',
            'email' => 'required|email|max:255|unique:branches',
            'phone' => 'required|max:25|unique:branches',
            'city' => 'required|max:35',
            'zip_code' => 'required|integer',
            'address' => 'required|max:255',
        ];
    }
}
