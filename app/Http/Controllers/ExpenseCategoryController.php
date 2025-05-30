<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExpenseCategoryController extends Controller
{
    public function index()
    {
        $expenseCategories = auth()->user()->restaurant->expenseCategories()->with('parent')->get();
        return theme_view('expense-categories.index', compact('expenseCategories'));
    }

    public function create()
    {
        $expenseCategories = auth()->user()->restaurant->expenseCategories()->get();
        return theme_view('expense-categories.form', compact('expenseCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:expense_categories,id',
        ]);

        $request->merge(['restaurant_id' => auth()->user()->restaurant->id]);
        auth()->user()->restaurant->expenseCategories()->create($request->all());

        return redirect('expense-categories')->with('success', 'Expense category created successfully.');
    }

    public function edit($id)
    {
        $expenseCategory = auth()->user()->restaurant->expenseCategories()->findOrFail($id);
        $expenseCategories = auth()->user()->restaurant->expenseCategories()->get();
        return theme_view('expense-categories.form', compact('expenseCategory', 'expenseCategories'));
    }

    public function show($id)
    {
        $expenseCategory = auth()->user()->restaurant->expenseCategories()->findOrFail($id);
        return theme_view('expense-categories.form', compact('expenseCategory'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:expense_categories,id',
        ]);

        $expenseCategory = auth()->user()->restaurant->expenseCategories()->findOrFail($id);
        $expenseCategory->update($request->all());

        return redirect('expense-categories')->with('success', 'Expense category updated successfully.');
    }

    public function destroy($id)
    {
        $expenseCategory = auth()->user()->restaurant->expenseCategories()->findOrFail($id);
        if ($expenseCategory->is_default) {
            return back()->with('error', 'Default category cannot be deleted.');
        }
        $expenseCategory->delete();

        return redirect('expense-categories')->with('success', 'Expense category deleted successfully.');
    }
}
