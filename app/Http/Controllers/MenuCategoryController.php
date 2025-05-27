<?php

namespace App\Http\Controllers;

use App\Models\MenuCategory;
use Illuminate\Http\Request;

class MenuCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $menuCategories = MenuCategory::where('outlet_id', outlet()->id)
            ->orderBy('name')
            ->get();
        $currentOutlet = outlet();
        return theme_view('menu-categories.index', compact('menuCategories', 'currentOutlet'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $menuCategories = MenuCategory::where('outlet_id', outlet()->id)->get();
        return theme_view('menu-categories.form')->with(['menuCategories' => $menuCategories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);
        $request->merge(['outlet_id' => outlet()->id]);
        MenuCategory::create($request->all());
        return redirect('menu-categories')->with('success', 'Successfully added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $menuCategory = MenuCategory::findOrFail($id);
        return theme_view('menu-categories.form')->with(compact('menuCategory'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $menuCategory = MenuCategory::findOrFail($id);
        $menuCategories = MenuCategory::where('outlet_id', outlet()->id)->get();
        return theme_view('menu-categories.form', compact('menuCategory', 'menuCategories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Logic to update a menu category
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $menuCategory = MenuCategory::findOrFail($id);
        if ($menuCategory->is_default) {
            return back()->with('error', 'Default category cannot be deleted.');
        }
        $menuCategory->delete();
        return redirect('menu-categories')->with('success', 'Successfully deleted');
    }
}
