<?php

namespace App\Http\Requests\Olap;

use Illuminate\Foundation\Http\FormRequest;

class DeliveriesDeliveriesRequest extends FormRequest
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
            'user_id' => 'filled|integer|exists:users,id',
            'date_from' => 'filled|date|date_format:Y-m-d',
            'date_to' => 'filled|date|date_format:Y-m-d|after_or_equal:date_from',
            'kitchen_code' => 'filled|string|exists:kitchens,code',
        ];
    }
}
