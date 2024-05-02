<?php

namespace Tests\Feature;

use App\Shop\ShopType;
use App\Models\Shop;
use App\Models\Postcode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShopControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a nearby shop is returned.
     */
    public function test_nearby_shop(): void
    {
        $shop = new Shop();
        $shop->name = 'Shop at DL1 1AA';
        $shop->latitude = 54.52360000;
        $shop->longitude = -1.55950000;
        $shop->is_open = true;
        $shop->shop_type = ShopType::TAKEAWAY;
        $shop->max_delivery_meters = 1000;
        $shop->save();

        $postcode = new Postcode();
        $postcode->postcode = 'DL11AB';
        $postcode->latitude = 54.52658000;
        $postcode->longitude = -1.55326600;
        $postcode->save();

        $response = $this->get(route('shops.near.postcode', ['postcode' => 'DL11AB']));

        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }

    /**
     * Test that a far away shop is not returned.
     */
    public function test_distant_shop_not_returned(): void
    {
        $shop = new Shop();
        $shop->name = 'Shop very far away';
        $shop->latitude = 0;
        $shop->longitude = 0;
        $shop->is_open = true;
        $shop->shop_type = ShopType::TAKEAWAY;
        $shop->max_delivery_meters = 1000;
        $shop->save();

        $postcode = new Postcode();
        $postcode->postcode = 'DL11AB';
        $postcode->latitude = 54.52658000;
        $postcode->longitude = -1.55326600;
        $postcode->save();

        $response = $this->get(route('shops.near.postcode', ['postcode' => 'DL11AB']));

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    /**
     * Test that a shop that delivers to a postcode is returned.
     */
    public function test_shop_delivers(): void
    {
        $shop = new Shop();
        $shop->name = 'Shop at DL1 1AA';
        $shop->latitude = 54.52360000;
        $shop->longitude = -1.55950000;
        $shop->is_open = true;
        $shop->shop_type = ShopType::TAKEAWAY;
        $shop->max_delivery_meters = 1000;
        $shop->save();

        $postcode = new Postcode();
        $postcode->postcode = 'DL11AB';
        $postcode->latitude = 54.52658000;
        $postcode->longitude = -1.55326600;
        $postcode->save();

        $response = $this->get(route('shops.delivery.postcode', ['postcode' => 'DL11AB']));

        $response->assertStatus(200);
        $response->assertJsonCount(1);
    }

    /**
     * Test that a shop whose delivery range is exceeded is not returned.
     */
    public function test_shop_does_not_deliver_outside_range(): void
    {
        $shop = new Shop();
        $shop->name = 'Shop at DL1 1AA';
        $shop->latitude = 54.52360000;
        $shop->longitude = -1.55950000;
        $shop->is_open = true;
        $shop->shop_type = ShopType::TAKEAWAY;
        $shop->max_delivery_meters = 1;
        $shop->save();

        $postcode = new Postcode();
        $postcode->postcode = 'DL11AB';
        $postcode->latitude = 54.52658000;
        $postcode->longitude = -1.55326600;
        $postcode->save();

        $response = $this->get(route('shops.delivery.postcode', ['postcode' => 'DL11AB']));

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }
}
