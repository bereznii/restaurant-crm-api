<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'first_name' => 'filled|string|max:255',
            'last_name' => 'filled|string|max:255',
            'email' => 'filled|string|email|max:255|unique:users',
            'phone' => 'filled|numeric|digits:12|unique:users',
            'position' => 'filled|string|max:255',
            'password' => 'filled|string|min:8',
            'role_name' => 'filled|string|exists:rbac_roles,name',
        ];
    }
}
