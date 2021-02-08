<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    protected $param = 'user';
    protected $model = User::class;

    protected $rules = [
        'name' => 'required',
        'email' => 'required',
        'password' => 'required',
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ($this->isMethod('POST')) {
            return $this->user()->can('create', $this->model);
        }

        $this->model = $this->route($this->param);
        return $this->user()->can('update', $this->model);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->isMethod('POST')) {
            return $this->rules;
        }

        return array_merge($this->rules, [
            //
        ]);
    }
}
