<?php

namespace App\Http\Resources;

use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TripRecordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $model = parent::toArray($request);

        $start_station = Station::find($model['start_station_id'], $model['start_station_sub_id']);
        $end_station = Station::find($model['end_station_id'], $model['end_station_sub_id']);

        if ((!$start_station || !$end_station) && !app()->hasDebugModeEnabled()) {
            abort(500, 'Station not found');
        } else {
            $start_station = $start_station?->toArray() ?? [ 'id' => 1, 'sub_id' => 0, 'name' => null ];
            $end_station = $end_station?->toArray() ?? [ 'id' => 1, 'sub_id' => 0, 'name' => null ];
        }

        return [
            'id' => (string)$model['id'],
            'rideable_type' => $model['rideable_type'],
            'started_at' => $model['started_at'],
            'ended_at' => $model['ended_at'],
            'start_station' => [
                'id' => $start_station['id'],
                'sub_id' => $start_station['sub_id'],
                'name' => $start_station['name'],
            ],
            'end_station' => [
                'id' => $end_station['id'],
                'sub_id' => $end_station['sub_id'],
                'name' => $end_station['name'],
            ],
            'start_location' => [
                'latitude' => $model['start_location_latitude'],
                'longitude' => $model['start_location_longitude'],
            ],
            'end_location' => [
                'latitude' => $model['end_location_latitude'],
                'longitude' => $model['end_location_longitude'],
            ],
            'member_casual' => $model['member_casual'],
        ];
    }
}
