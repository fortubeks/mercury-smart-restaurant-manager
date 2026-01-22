<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseExpenseItem;
use App\Models\ExpenseItem;
use App\Services\OutgoingPaymentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Expense $model)
    {
        $restaurantId = auth()->user()->restaurant_id;

        $metrics = Cache::remember("expenseMetrics_{$restaurantId}", 600, function () {
            return $this->getExpensesMetrics();
        });

        return theme_view('expenses.index', [
            'expenses' => $model->where('restaurant_id', $restaurantId)->orderBy('expense_date', 'desc')->limit(30)->get(),
            'metrics' => $metrics
        ]);
    }

    public function allExpenses()
    {
        $restaurantId = auth()->user()->restaurant_id;

        return theme_view('expenses.all-expenses', [
            'expenses' => Expense::where('restaurant_id', $restaurantId)->orderBy('expense_date', 'desc')->get(),
        ]);
    }

    public function getExpensesMetrics()
    {
        $metrics = [
            'today' => 0,
            'this_week' => 0,
            'this_month' => 0,
            'avg_weekly' => 0,
            'topMonthlyExpenseItems' => [],
            'topYearlyExpenseItems' => [],
        ];

        $restaurantId = auth()->user()->restaurant_id;

        // Get the start and end of the current month
        $startOfWeek = Carbon::now()->startOfWeek()->toDateString();
        $endOfWeek = Carbon::now()->endOfWeek()->toDateString();

        // Assuming we consider the expenses of the last 4 weeks to calculate the average
        $fourWeeksAgo = Carbon::now()->copy()->subWeeks(4)->startOfWeek()->toDateString();

        $startOfMonth = Carbon::now()->startOfMonth()->toDateString();
        $endOfMonth = Carbon::now()->endOfMonth()->toDateString();

        // Get the start and end of the current year
        $startOfYear = Carbon::now()->startOfYear()->toDateString();
        $endOfYear = Carbon::now()->endOfYear()->toDateString();

        // Get the date 6 months ago
        $sixMonthsAgo = Carbon::now()->subMonths(6)->startOfMonth()->toDateString();

        // Calculate expenses for today
        $metrics['today'] = Expense::whereDate('expense_date', Carbon::now()->toDateString())
            ->where('restaurant_id', $restaurantId) // Assuming you want to filter by the current user's restaurant
            ->sum('amount');

        // Calculate expenses for this week
        $metrics['this_week'] = Expense::whereBetween('expense_date', [
            $startOfWeek,
            $endOfWeek
        ])->where('restaurant_id', $restaurantId)
            ->sum('amount');

        // Calculate expenses for this month
        $metrics['this_month'] = Expense::whereBetween('expense_date', [
            $startOfMonth,
            $endOfMonth
        ])->where('restaurant_id', $restaurantId)
            ->sum('amount');

        // Calculate average weekly expenses

        $totalLastFourWeeks = Expense::whereBetween('expense_date', [
            $fourWeeksAgo,
            $endOfWeek
        ])->where('restaurant_id', $restaurantId)
            ->sum('amount');

        $metrics['avg_weekly'] = $totalLastFourWeeks / 4;

        // Top 5 expense items for the month
        $topMonthlyExpenseItems = ExpenseExpenseItem::select('expense_expense_items.expense_item_id', DB::raw('SUM(expense_expense_items.amount) as total_amount'), DB::raw('SUM(expense_expense_items.qty) as total_count'))
            ->join('expenses', 'expense_expense_items.expense_id', '=', 'expenses.id') // Joining Expense table
            ->where('expenses.restaurant_id', $restaurantId)
            ->whereBetween('expenses.expense_date', [$startOfMonth, $endOfMonth]) // Corrected column reference to expenses
            ->groupBy('expense_expense_items.expense_item_id')
            ->orderBy('total_amount', 'desc')
            ->limit(5)
            ->get();

        // Top 5 expense items for the year
        $topYearlyExpenseItems = ExpenseExpenseItem::select('expense_expense_items.expense_item_id', DB::raw('SUM(expense_expense_items.amount) as total_amount'), DB::raw('SUM(expense_expense_items.qty) as total_count'))
            ->join('expenses', 'expense_expense_items.expense_id', '=', 'expenses.id') // Joining Expense table
            ->where('expenses.restaurant_id', $restaurantId)
            ->whereBetween('expenses.expense_date', [$startOfYear, $endOfYear]) // Corrected column reference to expenses
            ->groupBy('expense_expense_items.expense_item_id')
            ->orderBy('total_amount', 'desc')
            ->limit(5)
            ->get();


        // If you want to include the names of the expense items (assuming you have a relationship with an ExpenseItem model):
        $topMonthlyExpenseItems = $topMonthlyExpenseItems->load('expenseItem');
        $topYearlyExpenseItems = $topYearlyExpenseItems->load('expenseItem');

        $metrics['topMonthlyExpenseItems'] = $topMonthlyExpenseItems;
        $metrics['topYearlyExpenseItems'] = $topYearlyExpenseItems;

        // Query to get total expenses for each month in the last 6 months
        $expensesLastSixMonths = Expense::select(
            DB::raw('DATE_FORMAT(expense_date, "%Y-%m") as month'),
            DB::raw('SUM(amount) as total_amount')
        )
            ->where('restaurant_id', $restaurantId)
            ->where('expense_date', '>=', $sixMonthsAgo)
            ->groupBy(DB::raw('DATE_FORMAT(expense_date, "%Y-%m")'))
            ->orderBy(DB::raw('DATE_FORMAT(expense_date, "%Y-%m")'))
            ->get();

        // Format the data for easier consumption (optional)
        $formattedExpenses = [];
        foreach ($expensesLastSixMonths as $expense) {
            $formattedExpenses[] = [
                'month' => $expense->month,
                'total_amount' => $expense->total_amount,
            ];
        }
        //dd($metrics);
        // Now you can return or use the $metrics array as needed
        return $metrics;
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return theme_view('expenses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'expense_date' => 'required',
            'category_id' => 'required',
            'amount' => 'required',
        ]);

        $total_amount = is_array($request->amount) ? array_sum($request->amount) : $request->amount;
        DB::beginTransaction();
        try {
            $expense = Expense::create([
                'restaurant_id' => restaurantId(),
                'expense_date' => $request->expense_date,
                'amount' => $total_amount,
                'supplier_id' => $request->supplier_id,
                'note' => $request->note,
                'expense_category_id' => $request->category_id,
            ]);

            foreach ($request->description as $key => $description) {
                if ($request->description[$key] === null) {
                    continue;
                }

                $expense_item = ExpenseItem::firstOrCreate(
                    ['restaurant_id' => restaurantId(), 'name' => $description, 'expense_category_id' => $request->category_id]
                );

                ExpenseExpenseItem::create([
                    'expense_id' => $expense->id,
                    'expense_item_id' => $expense_item->id,
                    'restaurant_id' => auth()->user()->restaurant_id,
                    'qty' => $request->qty[$key],
                    'rate' => $request->rate[$key],
                    'amount' => $request->amount[$key],
                    'unit_qty' => $request->unit_qty[$key],
                ]);
            }

            // Handle file upload if applicable
            if ($request->hasFile('uploaded_file')) {
                // Process file upload
            }

            //Handle payment
            if ($request->payment_amount) {
                $validatedPaymentRequest = $request->validate([
                    'payment_amount' => 'required|numeric',
                    'payment_method' => 'required|string',
                    'date_of_payment' => 'required',
                    'bank_account_id' => 'required'
                ]);
                $data = array_merge($validatedPaymentRequest, [
                    'restaurant_id' => restaurantId(),
                    'expense_id' => $expense->id,
                    'amount' => $request->payment_amount,
                ]);

                $payment = (new OutgoingPaymentService)->createForExpense($data);
                if (!$payment) {
                    return back()->with('error', 'Error adding expense. Contact support');
                }
            }

            DB::commit();

            //if request has maintenance issue id, update the issue
            if ($request->maintenance_issue_id) {
                $maintenanceIssue = MaintenanceIssue::find($request->maintenance_issue_id);
                if ($maintenanceIssue) {
                    $maintenanceIssue->update([
                        'expense_id' => $expense->id,
                    ]);
                }
                return redirect()->route('maintenance-issues.index')->with('success_message', 'Expense added successfully');
            }

            return redirect()->route('expenses.index')->with('success_message', 'Expense added successfully');
        } catch (\Exception $e) {
            // Log the exception message for debugging purposes
            logger()->error($e);
            // Rollback the transaction
            DB::rollBack();

            // Redirect the user back to the previous page with an error message
            return back()->withErrors([
                'error' => $e->getMessage(),
            ]);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        return view('dashboard.expenses.show', [
            'expense' => $expense,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        $amount = 0;
        foreach ($request->amount as $_amount) {
            $amount += $_amount;
        }
        foreach ($request->new_item_amount as $_amount_) {
            $amount += $_amount_;
        }

        DB::beginTransaction();
        try {
            $expense->update(
                [
                    'category_id' => $request->category_id,
                    'expense_date' => $request->expense_date,
                    'supplier_id' => $request->supplier_id,
                    'amount' => $amount,
                    'note' => $request->note
                ]
            );
            //update exisitng exense items
            foreach ($request->item_id as $key => $item_id) {
                $expense_item = ExpenseExpenseItem::find($item_id);

                $expense_item->update([
                    'qty' => $request->qty[$key],
                    'rate' => $request->rate[$key],
                    'amount' => $request->amount[$key],
                    'unit_qty' => $request->unit_qty[$key]
                ]);
            }

            foreach ($request->new_item as $_key => $new_item) {
                if ($request->new_item_description[$_key] === null) {
                    continue;
                }

                $expense_item = ExpenseItem::firstOrCreate(
                    ['restaurant_id' => restaurantId(), 'name' => $request->new_item_description[$_key]]
                );

                ExpenseExpenseItem::create([
                    'expense_id' => $expense->id,
                    'expense_item_id' => $expense_item->id,
                    'restaurant_id' => auth()->user()->restaurant_id,
                    'qty' => $request->new_item_qty[$_key],
                    'rate' => $request->new_item_rate[$_key],
                    'amount' => $request->new_item_amount[$_key],
                    'unit_qty' => $request->new_item_unit_qty[$_key]
                ]);
            }
            // Handle file upload if applicable
            if ($request->hasFile('uploaded_file')) {
                // Process file upload
            }

            DB::commit();

            //if request has maintenance issue id, update the issue
            if ($request->maintenance_issue_id) {
                $maintenanceIssue = MaintenanceIssue::find($request->maintenance_issue_id);
                if ($maintenanceIssue) {
                    $maintenanceIssue->update([
                        'expense_id' => $expense->id,
                    ]);
                }
                return redirect()->route('maintenance-issues.index')->with('success_message', 'Expense added successfully');
            }

            return redirect()->route('expenses.index', $expense->id)->with('success_message', 'Expense updated successfully');
        } catch (\Exception $e) {
            // Log the exception message for debugging purposes
            logger()->error($e);
            // Rollback the transaction
            DB::rollBack();

            // Redirect the user back to the previous page with an error message
            return back()->withErrors([
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();
        return redirect('expenses')->with('success', 'Deleted successfully');
    }

    public function search(Request $request)
    {
        $date = $request->input('search_expenses_by_date');
        $otherSearchValue = $request->input('search_expenses_by_name');

        $query = Expense::where('restaurant_id', auth()->user()->restaurant_id);

        // Apply date filter if a date is provided
        if ($date) {
            $query->whereDate('expense_date', $date);
        }

        // Apply other search criteria if provided
        if ($otherSearchValue) {
            $query->where(function ($q) use ($otherSearchValue) {
                $q->whereHas('category', function ($q) use ($otherSearchValue) {
                    $q->where('name', 'LIKE', "%$otherSearchValue%");
                })
                    ->orWhereHas('supplier', function ($q) use ($otherSearchValue) {
                        $q->where('name', 'LIKE', "%$otherSearchValue%");
                    })
                    ->orWhere('amount', 'LIKE', "%$otherSearchValue%");
            });
        }

        // Get the total amount based on the applied filters
        $totalAmount = $query->sum('amount');

        // Get the expenses based on the applied filters
        $expenses = $query->paginate(50);

        // Return JSON response with total amount and HTML content
        return response()->json([
            'totalAmount' => $totalAmount,
            'html' => view('dashboard.expenses.search-results', compact('expenses'))->render(),
        ]);
    }

    public function summary(Request $request)
    {
        // Get the current month start and end date
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        // Query to get expenses categorized and summed up for the selected date range
        $expenses = Expense::where('restaurant_id', auth()->user()->restaurant_id)
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->selectRaw('category_id, SUM(amount) as total_amount')
            ->groupBy('category_id')
            ->with('category') // Assuming there's a relationship to get the category name
            ->get();

        $totalExpenses = $expenses->sum('total_amount');

        return theme_view('expenses.summary', compact('expenses', 'startDate', 'endDate', 'totalExpenses'));
    }

    public function getExpenseItems(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->endOfMonth()->format('Y-m-d'));

        $expenses = Expense::where('restaurant_id', auth()->user()->restaurant_id)
            ->whereBetween('expense_date', [$startDate, $endDate])
            ->where('category_id', $request->category_id)->get();
        foreach ($expenses as $expense) {
            $expense->getItems = $expense->getItems();
        }

        return response()->json($expenses);
    }

    public function showUnpaidExpenses()
    {
        $restaurantId = restaurantId();
        $expenses = Expense::where('restaurant_id', $restaurantId)
            ->whereRaw('(
        SELECT COALESCE(SUM(amount), 0)
        FROM outgoing_payments
        WHERE payable_id = expenses.id
          AND payable_type = ?
    ) < expenses.amount', ['App\Models\Expense'])
            ->orderBy('expense_date', 'desc')
            ->get();

        return theme_view('expenses.unpaid-expenses', compact('expenses'));
    }
}
