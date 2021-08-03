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
            'title_ua' => 'filled|string|max:255',
            'title_ru' => 'filled|string|max:255',
            'prices.*.city_sync_id' => 'required|string|exists:cities,sync_id',
            'prices.*.price_old' => 'required|numeric',
            'is_active' => 'filled|in:0,1',
            'type_sync_id' => 'filled|in:pizza,sushi,soup,other',
            'description_ua' => 'filled|string|max:65000',
            'description_ru' => 'filled|string|max:65000',
        ];
    }
}
