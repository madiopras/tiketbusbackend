<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'schedule_id' => $this->schedule_id,
            'bus_id' => $this->bus_id,
            'route_id' => $this->route_id,
            'departure_time' => $this->departure_time,
            'arrival_time' => $this->arrival_time,
            'price' => $this->price,
            'description' => $this->description,
            'created_by_id' => $this->created_by_id,
            'updated_by_id' => $this->updated_by_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
