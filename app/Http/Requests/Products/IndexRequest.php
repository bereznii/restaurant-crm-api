<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
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
            'city_sync_id' => 'filled|exists:cities,sync_id',
            'restaurant' => 'filled|in:go,smaki',
            'type_sync_id' => 'filled|in:pizza,sushi,soup,other',
            'per_page' => 'filled|numeric|max:500',
            'page' => 'filled|numeric',
        ];
    }
}
