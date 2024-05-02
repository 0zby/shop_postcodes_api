<?php

namespace App\Http\Controllers;

use App\Models\Postcode;
use App\Models\Shop;
use App\Shop\ShopType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ShopController extends Controller
{
    /**
     * The distance in meters to consider a shop as nearby.
     */
    private const NEARBY_DISTANCE_METERS = 1000;

    public function index(): JsonResponse
    {
        return response()->json(Shop::all());
    }

    /**
     * Get all shops near a postcode.
     *
     * @param string $postcode The postcode to find shops near.
     * @return JsonResponse The shops near the postcode.
     */
    public function nearPostcode(string $postcode): JsonResponse
    {
        try {
            $postcode = Postcode::where('postcode', $postcode)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            Log::warning('Postcode not found.', ['postcode' => $postcode]);
            return response()->json(['message' => 'Postcode not found.'], 404);
        }

        $nearShops = Shop::whereRaw(
            'ST_Distance_Sphere(point(shops.latitude, shops.longitude), point(?, ?)) <= ?',
            [
                $postcode->latitude,
                $postcode->longitude,
                self::NEARBY_DISTANCE_METERS,
            ],
        )->get();

        return response()->json($nearShops);
    }

    /**
     * Get all shops that deliver to a postcode.
     *
     * @param string $postcode The postcode to find shops that deliver to.
     * @return JsonResponse The shops that deliver to the postcode.
     */
    public function deliveryPostcode(string $postcodeString): JsonResponse
    {
        $preparedPostcode = $this->preparePostcode($postcodeString);

        try {
            $postcode = Postcode::where('postcode', $preparedPostcode)->firstOrFail();
        } catch (ModelNotFoundException $exception) {
            Log::warning('Postcode not found.', ['postcode' => $preparedPostcode]);
            return response()->json(['message' => 'Postcode not found.'], 404);
        }

        $shops = Shop::open()
            ->whereRaw(
                'ST_Distance_Sphere(point(shops.latitude, shops.longitude), point(?, ?)) <= shops.max_delivery_meters',
                [
                    $postcode->latitude,
                    $postcode->longitude,
                ],
            )->get();

        return response()->json($shops);
    }

    /**
     * Store a new shop.
     *
     * @param Request $request The request containing the shop data.
     * @return JsonResponse The stored shop.
     */
    public function store(Request $request): JsonResponse
    {
        // I would like to make a custom request to remove the validation from the controller.
        $request->validate([
            'name' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'is_open' => 'required|boolean',
            'shop_type' => ['required', 'string', Rule::enum(ShopType::class)],
            'max_delivery_meters' => 'required|integer',
        ]);

        $shop = new Shop();
        $shop->name = $request->name;
        $shop->latitude = $request->latitude;
        $shop->longitude = $request->longitude;
        $shop->is_open = $request->is_open;
        $shop->shop_type = $request->shop_type;
        $shop->max_delivery_meters = $request->max_delivery_meters;
        $shop->save();

        // I would like to add a custom response object
        return response()->json($shop, 201);
    }

    /**
     * Prepare the postcode for use.
     *
     * @param string $postcode The postcode to prepare.
     * @return string The prepared postcode.
     */
    private function preparePostcode(string $postcode): string
    {
        $formattedPostcode = Str::remove(' ', $postcode);
        $formattedPostcode = Str::upper($formattedPostcode);

        return $formattedPostcode;
    }
}
