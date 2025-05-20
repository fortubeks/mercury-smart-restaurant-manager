<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function editRestaurant()
    {
        $restaurant = restaurant();
        return theme_view('settings.restaurant-information')->with([
            'restaurant' => $restaurant,
        ]);
    }

    public function updateRestaurant(Request $request)
    {
        //dd($request->all());
        // Validate and update restaurant information
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'country_id' => 'required|exists:countries,id',
            'state_id' => 'required|exists:states,id',
            'type' => 'required|string|max:255',
        ]);
        $restaurant = restaurant();
        $restaurant->update($request->all());

        return redirect()->route('settings.restaurant.edit')->with('success', 'Restaurant information updated successfully.');
    }
}
