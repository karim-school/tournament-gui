<?php

namespace App\Models;

use App\Http\Resources\TripRecordResource;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $rideable_type
 * @property string $started_at
 * @property string $ended_at
 * @property Station $start_station
 * @property Station $end_station
 * @property string $member_casual
 */
#[UseResource(TripRecordResource::class)]
#[Fillable([
    'rideable_type',
    'started_at',
    'ended_at',
    'start_station_id',
    'start_station_sub_id',
    'end_station_id',
    'end_station_sub_id',
    'member_casual',
])]
class TripRecord extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $appends = ['start_station_data', 'end_station_data'];

    public function startStation(): BelongsTo
    {
        return $this->belongsTo(Station::class, 'start_station_id', 'id');
    }

    public function endStation(): BelongsTo
    {
        return $this->belongsTo(Station::class, 'end_station_id', 'id');
    }

    public function getStartStationDataAttribute(): ?array
    {
        $station = Station::where('id', $this->start_station_id)
            ->where('sub_id', $this->start_station_sub_id)
            ->first();

        return $station ? [
            'id' => $station->id,
            'sub_id' => $station->sub_id,
            'name' => $station->name,
            'location' => [
                'latitude' => $station->latitude,
                'longitude' => $station->longitude,
            ],
        ] : null;
    }

    public function getEndStationDataAttribute(): ?array
    {
        $station = Station::where('id', $this->end_station_id)
            ->where('sub_id', $this->end_station_sub_id)
            ->first();

        return $station ? [
            'id' => $station->id,
            'sub_id' => $station->sub_id,
            'name' => $station->name,
            'location' => [
                'latitude' => $station->latitude,
                'longitude' => $station->longitude,
            ],
        ] : null;
    }
}
