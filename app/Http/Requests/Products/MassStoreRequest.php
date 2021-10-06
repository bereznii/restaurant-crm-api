<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MassStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->hasPermissionTo('api_access');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            '*.product_uuid' => 'required|uuid',
            '*.title_ua' => 'required|string|max:255',
            '*.article' => 'required|string|max:255',
            '*.weight' => 'required|numeric',
            '*.weight_type' => 'required|string|max:20',

            //TODO: если прийдёт новый город, синхронка отпадёт - исправить
            '*.prices.*.city_uuid' => 'required|uuid|exists:cities,uuid',
            '*.prices.*.price' => 'required|numeric',
        ];
    }
}
