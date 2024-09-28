<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'bus_id' => 'required|integer',
            'departure_time' => 'required|date',
            'arrival_time' => 'required|date',
            'description' => 'required|string|max:255',
        ];
    }
}
