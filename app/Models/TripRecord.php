<?php

namespace App\Models;

use App\Http\Resources\TripRecordResource;
use Database\Factories\TripRecordFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\UseResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $rideable_type
 * @property string $started_at
 * @property string $ended_at
 * @property Station $start_station
 * @property Station $end_station
 * @property string $start_location
 * @property string $end_location
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
    'start_location_latitude',
    'start_location_longitude',
    'end_location_latitude',
    'end_location_longitude',
    'member_casual',
])]
class TripRecord extends Model
{
    /**
     * @use HasFactory<TripRecordFactory>
     */
    use HasFactory;

    public $timestamps = false;
}
