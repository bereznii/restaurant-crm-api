<?php

namespace App\Http\Requests\Orders\OrderItems;

use App\Models\Order\OrderItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->hasPermissionTo('kitchen_section');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "status" => "required|string|in:" . implode(',', array_column(OrderItem::STATUSES, 'name'))
        ];
    }
}
