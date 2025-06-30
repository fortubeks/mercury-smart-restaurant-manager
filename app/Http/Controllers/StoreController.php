<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function dashboard()
    {
        return theme_view('store.dashboard');
    }

    public function index()
    {
        $stores = restaurant()->stores()->orderBy('created_at', 'desc')->get();
        return theme_view('stores.index')->with([
            'stores' => $stores,
        ]);
    }

    public function create()
    {
        return theme_view('stores.form');
    }

    public function edit($id)
    {
        $store = restaurant()->stores()->findOrFail($id);
        return theme_view('stores.form')->with([
            'store' => $store,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $store = restaurant()->stores()->create($request->all());

        if ($request->ajax()) {
            return response()->json($store);
        }

        return redirect()->route('stores.index')->with('success_message', 'Store created successfully.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $store = restaurant()->stores()->findOrFail($id);
        $store->update($request->all());

        if ($request->ajax()) {
            return response()->json($store);
        }

        return redirect()->route('stores.index')->with('success_message', 'Store updated successfully.');
    }
}
