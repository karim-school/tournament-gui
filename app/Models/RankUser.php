<?php

namespace App\Models;

use Database\Factories\RankUserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property long $id
 * @property string $name
 * @property int $points
 * @property int $wins
 * @property int $games
 */
#[Fillable(['name'])]
class RankUser extends Model
{
    /**
     * @use HasFactory<RankUserFactory>
     */
    use HasFactory;
}
