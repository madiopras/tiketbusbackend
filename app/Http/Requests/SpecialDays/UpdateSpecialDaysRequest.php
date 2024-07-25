<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSpecialDayRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date',
            'description' => 'sometimes|required|string|max:255',
            'price_percentage' => 'sometimes|required|numeric',
            'is_increase' => 'sometimes|required|boolean',
            'is_active' => 'sometimes|required|boolean',
        ];
    }
}
