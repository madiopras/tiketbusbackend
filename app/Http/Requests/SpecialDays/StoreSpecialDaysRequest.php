<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSpecialDayRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'description' => 'required|string|max:255',
            'price_percentage' => 'required|numeric',
            'is_increase' => 'required|boolean',
            'is_active' => 'required|boolean',
        ];
    }
}
