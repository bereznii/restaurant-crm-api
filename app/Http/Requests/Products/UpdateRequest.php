<?php

namespace App\Http\Requests\Products;

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
            'title_ua' => 'required|string|max:255',
            'title_ru' => 'required|string|max:255',
            'prices.*.city_sync_id' => 'required|string|exists:cities,sync_id',
            'prices.*.price_old' => 'required|numeric',
            'prices.*.is_active' => 'required|in:0,1',
            'description_ua' => 'required|string|max:65000',
            'description_ru' => 'required|string|max:65000',
            'type_sync_id' => 'required|exists:product_types,sync_id',
            'category_sync_id' => 'required|exists:product_categories,sync_id',
        ];
    }
}
