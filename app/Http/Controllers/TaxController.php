<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Illuminate\Http\Request;

class TaxController extends Controller
{
    public function index(Tax $taxes)
    {
        return theme_view('settings.taxes.index', [
            'taxes' => $taxes->where('restaurant_id', auth()->user()->restaurant_id)
                ->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return theme_view('settings.taxes.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric',
            'is_active' => 'nullable|boolean',
        ]);

        $validatedData['restaurant_id'] = restaurantId();
        $validatedData['is_active'] = $request->boolean('is_active');

        Tax::create($validatedData);

        return redirect()->route('taxes.index')->with('success', 'Tax created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tax $tax)
    {
        return theme_view('settings.taxes.form', [
            'tax' => $tax,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tax $tax)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric',
            'is_active' => 'nullable|boolean',
        ]);

        $validatedData['is_active'] = $request->boolean('is_active');

        $tax->update($validatedData);

        return redirect()->route('taxes.index')->with('success', 'Tax updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tax $tax)
    {
        $tax->delete();
    }
}
