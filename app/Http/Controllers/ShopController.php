<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        return response()->json(Shop::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'is_open' => 'required|boolean',
            'store_type' => 'required|string',
            'max_delivery_meters' => 'required|integer',
        ]);

        $shop = new Shop();
        $shop->name = $request->name;
        $shop->latitude = $request->latitude;
        $shop->longitude = $request->longitude;
        $shop->is_open = $request->is_open;
        $shop->store_type = $request->store_type;
        $shop->max_delivery_meters = $request->max_delivery_meters;
        $shop->save();

        return response()->json($shop, 201);
    }
}
