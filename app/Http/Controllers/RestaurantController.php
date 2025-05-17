<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    public function editRestaurant()
    {
        return theme_view('settings.restaurant-information');
    }

    public function updateRestaurant(Request $request)
    {
        // Validate and update restaurant information
        // ...

        return redirect()->route('settings.restaurant.edit')->with('success', 'Restaurant information updated successfully.');
    }
}
