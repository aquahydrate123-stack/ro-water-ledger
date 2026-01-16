<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExpenseLedgerController extends Controller
{
    public function show(Request $request, ExpenseCategory $expenseCategory)
    {
        if ($expenseCategory->user_id !== auth()->id()) {
            abort(403);
        }

        $query = Expense::where('expense_category_id', $expenseCategory->id)
            ->where('created_by', auth()->id());

        $filter = $request->get('filter', 'all');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        if ($filter === 'daily') {
            $query->whereDate('expense_date', Carbon::today());
        } elseif ($filter === 'monthly') {
            $query->whereMonth('expense_date', Carbon::now()->month)
                ->whereYear('expense_date', Carbon::now()->year);
        } elseif ($filter === 'yearly') {
            $query->whereYear('expense_date', Carbon::now()->year);
        } elseif ($filter === 'custom' && $startDate && $endDate) {
            $query->whereBetween('expense_date', [$startDate, $endDate]);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->get();
        $totalAmount = $expenses->sum('amount');

        return view('expenses.ledger', compact('expenseCategory', 'expenses', 'totalAmount', 'filter', 'startDate', 'endDate'));
    }
}
