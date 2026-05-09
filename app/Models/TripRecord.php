<?php

namespace App\Models;

use App\Enums\RideType;
use App\Http\Resources\TripRecordResource;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property int $user_id
 * @property Station $start_station
 * @property Station $end_station
 * @property \DateTime $started_at
 * @property \DateTime $ended_at
 * @property RideType $ride_type
 */
#[UseResource(TripRecordResource::class)]
#[Fillable([
    'user_id',
    'start_station_id',
    'end_station_id',
    'started_at',
    'ended_at',
    'ride_type',
])]
class TripRecord extends Model
{
    use HasFactory, HasUuids;

    protected $primaryKey = 'id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $appends = ['start_station_data', 'end_station_data'];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

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
        $station = Station::where('id', $this->start_station_id)->first();

        return $station ? [
            'id' => $station->id,
            'name' => $station->name,
            'location' => [
                'latitude' => $station->latitude,
                'longitude' => $station->longitude,
            ],
        ] : null;
    }

    public function getEndStationDataAttribute(): ?array
    {
        $station = Station::where('id', $this->end_station_id)->first();

        return $station ? [
            'id' => $station->id,
            'name' => $station->name,
            'location' => [
                'latitude' => $station->latitude,
                'longitude' => $station->longitude,
            ],
        ] : null;
    }
}
