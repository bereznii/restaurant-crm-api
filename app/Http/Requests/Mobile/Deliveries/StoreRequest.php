<?php

namespace App\Http\Requests\Mobile\Deliveries;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->hasPermissionTo('delivery_section');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'delivery_terminal_id' => 'required|uuid',
            'orders' => 'required',
            'orders.*' => 'required',
            'orders.*.restaurant' => 'required|string|in:go,smaki',
            'orders.*.order_uuid' => 'required|uuid',
            'orders.*.address' => 'required',
            'orders.*.address.city' => 'required|string',
            'orders.*.address.street' => 'required|string',
            'orders.*.address.index' => 'present',
            'orders.*.address.home' => 'required|string',
            'orders.*.address.housing' => 'present',
            'orders.*.address.apartment' => 'present',
            'orders.*.address.entrance' => 'present',
            'orders.*.address.floor' => 'present'
        ];
    }
}
