<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'filled|string|email|max:255|unique:users',
            'phone' => 'required|numeric|digits:12|unique:users',
            'position' => 'filled|string|max:255',
            'password' => 'required|string|min:8',
            'role_name' => 'required|string|exists:rbac_roles,name',
            'iiko_id' => 'required_if:role_name,courier|uuid|unique:courier_iiko',
            'product_types' => 'required_if:role_name,cook|array',
            'product_types.*' => 'string|exists:product_types,sync_id',
            'kitchen_code' => 'required|string|exists:kitchens,code',
            'status' => 'filled|string|in:active,disabled',
        ];
    }
}
