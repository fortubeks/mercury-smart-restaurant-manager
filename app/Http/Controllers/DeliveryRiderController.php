<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DeliveryRiderController extends Controller
{
    public function index()
    {
        // Fetch all delivery riders for the restaurant
        $deliveryRiders = restaurant()->deliveryRiders;

        return theme_view('delivery-riders.index', compact('deliveryRiders'));
    }

    public function create()
    {
        // Show form to create a new delivery rider
        return theme_view('delivery-riders.form');
    }

    public function store(Request $request)
    {
        // Validate and store the new delivery rider
        $data = $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'vehicle_type' => 'nullable|string|max:50',
            'vehicle_number' => 'nullable|string|max:50',
            'status' => 'nullable|string|in:active,inactive,busy',
            'profile_picture' => 'nullable|image|max:2048',
            'notes' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:15',
            'emergency_contact_name' => 'nullable|string|max:255',
        ]);

        // Handle file upload if profile picture is provided
        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        restaurant()->deliveryRiders()->create($data);

        return redirect()->route('delivery-riders.index')->with('success_message', 'Delivery Rider Created Successfully');
    }
    public function edit($id)
    {
        // Fetch the delivery rider for editing
        $deliveryRider = restaurant()->deliveryRiders()->findOrFail($id);

        return theme_view('delivery-riders.form', compact('deliveryRider'));
    }
    public function update(Request $request, $id)
    {
        // Validate and update the delivery rider
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'vehicle_type' => 'nullable|string|max:50',
            'vehicle_number' => 'nullable|string|max:50',
            'status' => 'nullable|string|in:active,inactive,busy',
            'profile_picture' => 'nullable|image|max:2048',
            'notes' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:15',
            'emergency_contact_name' => 'nullable|string|max:255',
        ]);

        // Handle file upload if profile picture is provided
        if ($request->hasFile('profile_picture')) {
            $data['profile_picture'] = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        $deliveryRider = restaurant()->deliveryRiders()->findOrFail($id);
        $deliveryRider->update($data);

        return redirect()->route('delivery-riders.index')->with('success_message', 'Delivery Rider Updated Successfully');
    }
    public function destroy($id)
    {
        // Delete the delivery rider
        $deliveryRider = restaurant()->deliveryRiders()->findOrFail($id);
        // if the rider has any orders assigned, prevent deletion
        if ($deliveryRider->orders()->count() > 0) {
            return redirect()->route('delivery-riders.index')->with('error_message', 'Cannot delete rider with assigned orders.');
        }
        $deliveryRider->delete();

        return redirect()->route('delivery-riders.index')->with('success_message', 'Delivery Rider Deleted Successfully');
    }
}
