<?php

namespace App\Http\Requests\Buses;

use Illuminate\Foundation\Http\FormRequest;

class StoreBusRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'bus_number' => 'required|string|max:255',
            'capacity' => 'required|integer',
            'operator_name' => 'required|string|max:255',
            'class_id' => 'required|integer',
            'is_active' => 'required|boolean',
        ];
    }
}
