<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * A postcode with coordinates.
 *
 * @property int $id ID of the postcode.
 * @property string $postcode The postcode.
 * @property float $latitude The latitude of the postcode.
 * @property float $longitude The longitude of the postcode.
 * @property \Carbon\Carbon $created_at When the record was created.
 * @property \Carbon\Carbon $updated_at When the record was last updated.
 */
class Postcode extends Model
{
    protected $fillable = [
        'postcode',
        'latitude',
        'longitude',
    ];
}
