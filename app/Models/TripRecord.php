<?php

namespace App\Models;

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
class TripRecord extends Model
{
    //
}
