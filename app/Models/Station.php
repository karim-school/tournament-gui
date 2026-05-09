<?php

namespace App\Models;

use Database\Factories\StationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property float $latitude
 * @property float $longitude
 */
class Station extends Model
{
    /**
     * @use HasFactory<StationFactory>
     */
    use HasFactory;

    public $timestamps = false;
}
