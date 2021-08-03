<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class MassStoreRequest extends FormRequest
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
            '*.title_ua' => 'required|string|max:255',
            '*.article' => 'required|string|max:255',
            '*.weight' => 'required|numeric',
            '*.weight_type' => 'required|string|max:20',
            '*.prices.*.city' => 'required|exists:cities,sync_id',
            '*.prices.*.price' => 'required|numeric',
        ];
    }
}
