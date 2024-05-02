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

        // This is not optimal - it feels like with a bit of maths we can query the database at least for a rough estimate of the shops that are near a postcode.
        $shops = Shop::all();
        $nearShops = $shops->filter(function ($shop) use ($postcode) {
            $distance = $shop->distanceTo($postcode->latitude, $postcode->longitude);
            // I've set distance to 3000 meters as a rough estimate of what "near" means but perhaps we should configure it or accept it in the request.
            return $distance <= 3000;
        });

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

        $postcode = Postcode::where('postcode', $preparedPostcode)->firstOrFail();

        // Again it feels like with some maths we can stop querying all shops and just query the ones that are within the delivery range or at least a good estimate.
        $shops = Shop::open()->get();
        $deliveryShops = $shops->filter(function ($shop) use ($postcode) {
            $distance = $shop->distanceTo($postcode->latitude, $postcode->longitude);
            return $distance <= $shop->max_delivery_meters;
        });

        return response()->json($deliveryShops);
    }

    /**
     * Store a new shop.
     *
     * @param Request $request The request containing the shop data.
     * @return JsonResponse The stored shop.
     */
    public function store(Request $request): JsonResponse
    {
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
