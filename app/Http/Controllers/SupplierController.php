<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(Supplier $model)
    {
        return theme_view('suppliers.index', [
            'suppliers' => $model->with('expenses', 'purchases')->where('restaurant_id', auth()->user()->restaurant_id)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return theme_view('suppliers.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Supplier $supplier)
    {
        $request->merge(['restaurant_id' => auth()->user()->restaurant_id]);
        $supplier->create($request->all());
        return redirect()->route('suppliers.index')->with('success_message', 'Supplier Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return theme_view('suppliers.form', [
            'supplier' => Supplier::findOrFail($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return theme_view('suppliers.form', [
            'supplier' => Supplier::findOrFail($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->merge(['restaurant_id' => auth()->user()->restaurant_id]);
        $supplier = Supplier::findOrFail($id);
        $supplier->update($request->all());
        return redirect()->route('suppliers.index')->with('success_message', 'Supplier Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supplier = Supplier::findOrFail($id);
        //if supplier has expenses or purchases, do not delete
        if ($supplier->expenses()->count() > 0 || $supplier->purchases()->count() > 0) {
            return redirect()->route('suppliers.index')->with('error_message', 'Cannot delete supplier with expenses or purchases');
        }
        //delete the supplier
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success_message', 'Supplier Deleted Successfully');
    }
}
