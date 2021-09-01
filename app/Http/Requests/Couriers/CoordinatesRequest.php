<?php

namespace App\Http\Requests\Couriers;

use Illuminate\Foundation\Http\FormRequest;

class CoordinatesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //TODO: добавить пользователя для внешних сервисов, котоыре будут к нам ходить
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
