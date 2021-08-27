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
            'orders' => 'required|array',
            'orders.*' => 'required|array:restaurant,order_uuid',
            'orders.*.restaurant' => 'required|string|in:go,smaki',
            'orders.*.order_uuid' => 'required|uuid',
        ];
    }
}
