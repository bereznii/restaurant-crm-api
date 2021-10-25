<?php

namespace App\Http\Requests\Orders;

use App\Models\Order\Order;
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
            'restaurant' => 'required|string|in:go,smaki',
            'kitchen_code' => 'required|string|exists:kitchens,code',
            'payment_type' => 'required|string|in:' . implode(',', array_column(Order::PAYMENT_TYPES, 'name')),
            'type' => 'required|string|in:' . implode(',', array_column(Order::TYPES, 'name')),
            'status' => 'required|string|in:' . implode(',', array_column(Order::STATUSES, 'name')),
            'return_call' => 'required|in:1,0',
            'courier_id' => 'required|integer|exists:users,id',
            'client_comment' => 'nullable|string|max:65000',
            'delivered_till' => 'required_if:type,requested_time|prohibited_if:type,soon|date|date_format:Y-m-d H:i:s',

            'address' => 'required|array',
            'address.city_sync_id' => 'required|string|exists:cities,sync_id',
            'address.street' => 'required|string',
            'address.house_number' => 'required|string',
            'address.entrance' => 'required|string',
            'address.floor' => 'required|string',
            'address.comment' => 'nullable|string|max:65000',

            'items' => 'required|array',
            'items.*.product_id' => 'required|uuid|exists:products,id',
            'items.*.quantity' => 'required|integer',
            'items.*.comment' => 'nullable|string|max:65000',
        ];
    }
}