<?php

namespace App\Models;

use App\Shop\ShopType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use PHPCoord\CoordinateReferenceSystem\Geographic2D;
use PHPCoord\Point\GeographicPoint;
use PHPCoord\UnitOfMeasure\Angle\Degree;

/**
 * A physical shop.
 *
 * @property int $id ID of the shop.
 * @property string $name The name of the shop.
 * @property float $latitude The latitude of the shop.
 * @property float $longitude The longitude of the shop.
 * @property bool $is_open Whether the shop is open.
 * @property ShopType $shop_type The type of the shop.
 * @property int $max_delivery_meters The maximum distance in meters the shop delivers to.
 * @property \Carbon\Carbon $created_at When the record was created.
 * @property \Carbon\Carbon $updated_at When the record was last updated.
 */
class Shop extends Model
{
    protected $fillable = [
        'name',
        'latitude',
        'longitude',
        'is_open',
        'shop_type',
        'max_delivery_meters',
    ];

    protected $casts = [
        'is_open' => 'boolean',
        'shop_type' => ShopType::class,
    ];

    /**
     * Scope a query to only include open shops.
     *
     * @param Builder $query The query to filter.
     */
    public function scopeOpen($query): Builder
    {
        return $query->where('is_open', true);
    }

    /**
     * Calculate the distance in meters to a coordinate.
     *
     * @param float $latitude The latitude of the coordinate.
     * @param float $longitude The longitude of the coordinate.
     * @return int The distance in meters, rounded up to a meter.
     */
    public function distanceTo(float $latitude, float $longitude): int
    {
        // Now doing this in the DB but this could be useful to keep.
        $crs = Geographic2D::fromSRID(Geographic2D::EPSG_ED50);
        $shopLocation = GeographicPoint::create($crs, new Degree($this->latitude), new Degree($this->longitude));
        $givenLocation = GeographicPoint::create($crs, new Degree($latitude), new Degree($longitude));
        $distance = $shopLocation->calculateDistance($givenLocation);

        return ceil($distance->asMetres()->getValue());
    }
}
