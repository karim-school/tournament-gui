<?php

namespace App\Models;

use Database\Factories\StationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $sub_id
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

    public static function findByIdAndSubId(int $id, int $sub_id): ?Station
    {
        return Station::query()->where(['id' => $id, 'sub_id' => $sub_id])->first();
    }
}
