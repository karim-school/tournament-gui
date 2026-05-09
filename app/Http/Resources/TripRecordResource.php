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
            'user' => (new UserResource($this->user))->resolve(),
            'start_station' => $this->start_station_data,
            'end_station' => $this->end_station_data,
            'started_at' => $this->started_at->getTimestamp() * 1000,
            'ended_at' => $this->ended_at->getTimestamp() * 1000,
            'ride_type' => $this->ride_type,
        ];
    }
}
