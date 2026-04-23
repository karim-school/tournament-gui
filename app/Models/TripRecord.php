<?php

namespace App\Models;

use App\Http\Resources\TripRecordResource;
use Database\Factories\TripRecordFactory;
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
class TripRecord extends Model
{
    /**
     * @use HasFactory<TripRecordFactory>
     */
    use HasFactory;

    public $timestamps = false;
}
