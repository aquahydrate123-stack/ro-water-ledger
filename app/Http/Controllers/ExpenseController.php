<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = Expense::where('created_by', Auth::id())->with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('category', function ($cq) use ($search) {
                    $cq->where('name', 'like', "%{$search}%");
                })
                    ->orWhere('expense_type', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        $expenses = $query->latest('expense_date')->get();
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        $categories = \App\Models\ExpenseCategory::where('user_id', Auth::id())->get();
        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'expense_type' => 'required|string',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric',
            'expense_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = Auth::id();

        Expense::create($validated);

        return redirect()->route('expenses.index')
            ->with('success', 'Expense recorded successfully.');
    }

    public function edit(Expense $expense)
    {
        if ($expense->created_by !== Auth::id()) {
            abort(403);
        }
        $categories = \App\Models\ExpenseCategory::where('user_id', Auth::id())->get();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        if ($expense->created_by !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'expense_type' => 'required|in:business,personal',
            'expense_category_id' => 'required|exists:expense_categories,id',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        if ($expense->created_by !== Auth::id()) {
            abort(403);
        }

        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}
