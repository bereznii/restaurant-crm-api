<?php

namespace App\Http\Requests\Users;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'email' => ['filled','string','email','max:255', Rule::unique('users')->ignore($this->getRequestedUser())],
            'phone' => ['filled','numeric','digits:12', Rule::unique('users')->ignore($this->getRequestedUser())],
            'position' => 'filled|string|max:255',
            'password' => 'filled|string|min:8',
            'role_name' => 'filled|string|exists:rbac_roles,name',
            'iiko_id' => 'required_if:role_name,courier|uuid',
            'kitchen_code' => 'required|string|exists:kitchens,code',
            'product_types' => 'required_if:role_name,cook|array',
            'product_types.*' => 'string|exists:product_types,sync_id',
            'status' => 'filled|string|in:active,disabled',
        ];
    }

    /**
     * @return User
     */
    private function getRequestedUser(): User
    {
        return User::findOrFail($this->route('user'));
    }
}
