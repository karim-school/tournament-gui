<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripRecordResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'rideable_type' => $this->rideable_type,
            'started_at' => $this->started_at,
            'ended_at' => $this->ended_at,
            'start_station' => $this->start_station_data,
            'end_station' => $this->end_station_data,
            'member_casual' => $this->member_casual,
        ];
    }
}
