<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'bus_id' => 'sometimes|required|integer',
            'route_id' => 'sometimes|required|integer',
            'departure_time' => 'sometimes|required|date',
            'arrival_time' => 'sometimes|required|date',
            'price' => 'sometimes|required|numeric',
            'description' => 'sometimes|required|string|max:255',
        ];
    }
}
