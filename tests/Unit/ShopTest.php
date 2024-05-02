<?php

namespace Tests\Unit;

use App\Models\Shop;
use PHPUnit\Framework\TestCase;

class ShopTest extends TestCase
{
    /**
     * Test shop distance calculation.
     *
     * @dataProvider distanceProvider
     */
    public function test_that_distances_are_calculated_correctly(float $latitudeA, float $longitudeA, float $latitudeB, float $longitudeB, int $expectedDistance): void
    {
        // Allow 5% leeway in the distance.
        $leeway = 0.05 * $expectedDistance;

        $shop = new Shop([
            'latitude' => $latitudeA,
            'longitude' => $longitudeA,
        ]);

        $distance = $shop->distanceTo($latitudeB, $longitudeB);

        $this->assertEqualsWithDelta($expectedDistance, $distance, $leeway);
    }

    /**
     * Data provider for distance test.
     *
     * @return array The data for the test.
     */
    public static function distanceProvider(): array
    {
        // Here I'm using coordinates from Google against distances from https://www.freemaptools.com
        return [
            'Darlington to Newcastle' => [
                54.5236,
                -1.5595,
                54.9783,
                -1.6178,
                50000,
            ],
            'London to Edinburgh' => [
                51.5072,
                -0.1276,
                55.9533,
                -3.1883,
                534000,
            ],
            'Manchester to Oxford' => [
                53.4808,
                -2.2426,
                51.7520,
                -1.2577,
                203000,
            ],
        ];
    }
}
