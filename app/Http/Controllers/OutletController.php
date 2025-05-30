<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OutletController extends Controller
{
    public function setOutlet(Request $request)
    {
        $outletId = $request->input('outlet_id');

        // Validate the outlet ID
        if (!$outletId) {
            return redirect()->back()->withErrors(['outlet_id' => 'Outlet ID is required']);
        }

        // Set the outlet in the session
        session(['outlet_id' => $outletId]);

        $request->user()->update(['outlet_id' => $outletId]);
        return response()->json(['message' => 'Session updated successfully']);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'is_sales_outlet' => 'boolean',
        ]);

        $data['restaurant_id'] = $request->user()->restaurant_id;

        \DB::transaction(function () use (&$outlet, $data) {
            $outlet = Outlet::create($data);
            if ($outlet->is_sales_outlet) {
                MenuCategory::create([
                    'outlet_id' => $outlet->id,
                    'name' => 'Default',
                    'is_default' => true,
                ]);
            }
        });

        return redirect()->route('outlets.index')->with('success', 'Outlet created successfully');
    }
}
