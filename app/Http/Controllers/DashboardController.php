<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Expense;
use App\Models\Sale;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = auth()->id();
        $filter = $request->get('filter', 'daily'); // Default to today
        $startDateStr = $request->get('start_date');
        $endDateStr = $request->get('end_date');

        $startDate = null;
        $endDate = null;
        $label = 'Today';

        if ($filter === 'daily') {
            $startDate = Carbon::today();
            $endDate = Carbon::today()->endOfDay();
            $label = 'Today';
        } elseif ($filter === 'monthly') {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfDay();
            $label = 'This Month';
        } elseif ($filter === 'last_month') {
            $startDate = Carbon::now()->subMonth()->startOfMonth();
            $endDate = Carbon::now()->subMonth()->endOfMonth();
            $label = 'Last Month';
        } elseif ($filter === 'yearly') {
            $startDate = Carbon::now()->startOfYear();
            $endDate = Carbon::now()->endOfDay();
            $label = 'This Year';
        } elseif ($filter === 'last_year') {
            $startDate = Carbon::now()->subYear()->startOfYear();
            $endDate = Carbon::now()->subYear()->endOfMonth();
            $label = 'Last Year';
        } elseif ($filter === 'custom' && $startDateStr && $endDateStr) {
            $startDate = Carbon::parse($startDateStr)->startOfDay();
            $endDate = Carbon::parse($endDateStr)->endOfDay();
            $label = 'Custom Period';
        } elseif ($filter === 'all') {
            $label = 'All Time';
        }

        // Metrics queries
        $salesQuery = Sale::where('user_id', $userId);
        $paymentsQuery = \App\Models\Payment::where('created_by', $userId);
        $expensesQuery = Expense::where('created_by', $userId);

        if ($startDate && $endDate) {
            $salesQuery->whereBetween('sale_date', [$startDate->toDateString(), $endDate->toDateString()]);
            $paymentsQuery->whereBetween('payment_date', [$startDate->toDateString(), $endDate->toDateString()]);
            $expensesQuery->whereBetween('expense_date', [$startDate->toDateString(), $endDate->toDateString()]);
        }

        // 1. Total Sales for period
        $totalSales = $salesQuery->sum('total_amount');

        // 2. Total Expenses for period
        $totalExpenses = $expensesQuery->sum('amount');

        // 3. Today Net Cash (Net Cash for Period)
        // Logic: (Cash Sales in period + Payments in period) - Expenses in period
        $cashSalesSum = Sale::where('user_id', $userId)
            ->where(function ($q) {
                $q->where('sale_type', 'refill')
                    ->orWhere('payment_type', 'cash');
            })
            ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                return $q->whereBetween('sale_date', [$startDate->toDateString(), $endDate->toDateString()]);
            })
            ->sum('total_amount');

        $paymentsSum = \App\Models\Payment::where('created_by', $userId)
            ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                return $q->whereBetween('payment_date', [$startDate->toDateString(), $endDate->toDateString()]);
            })
            ->sum('amount');

        $cashInHand = ($cashSalesSum + $paymentsSum) - $totalExpenses;

        // 4. Total Receivables as of End Date
        // Logic: (Total Credit Sales until endDate) - (Total Payments until endDate)
        $totalReceivableSales = Sale::where('user_id', $userId)
            ->where('payment_type', 'credit')
            ->when($endDate, function ($q) use ($endDate) {
                return $q->where('sale_date', '<=', $endDate->toDateString());
            })
            ->sum('total_amount');

        $totalReceivablePayments = \App\Models\Payment::where('created_by', $userId)
            ->when($endDate, function ($q) use ($endDate) {
                return $q->where('payment_date', '<=', $endDate->toDateString());
            })
            ->sum('amount');

        $totalReceivables = (float) $totalReceivableSales - (float) $totalReceivablePayments;

        // Recent Sales
        $recentSales = Sale::where('user_id', $userId)
            ->with('customer')
            ->orderByDesc('sale_date')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        // Analytics: Expenses by Category
        $expensesByCategory = Expense::where('created_by', $userId)
            ->when($startDate && $endDate, function ($q) use ($startDate, $endDate) {
                return $q->whereBetween('expense_date', [$startDate->toDateString(), $endDate->toDateString()]);
            })
            ->join('expense_categories', 'expenses.expense_category_id', '=', 'expense_categories.id')
            ->select('expense_categories.name', \DB::raw('SUM(expenses.amount) as total'))
            ->groupBy('expense_categories.name')
            ->orderByDesc('total')
            ->get();

        // Analytics: Monthly Sales vs Expenses (Last 6 months)
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $monthSales = Sale::where('user_id', $userId)
                ->whereMonth('sale_date', $month->month)
                ->whereYear('sale_date', $month->year)
                ->sum('total_amount');
            $monthExpenses = Expense::where('created_by', $userId)
                ->whereMonth('expense_date', $month->month)
                ->whereYear('expense_date', $month->year)
                ->sum('amount');

            $monthlyStats[] = [
                'month' => $month->format('M'),
                'sales' => $monthSales,
                'expenses' => $monthExpenses,
            ];
        }

        return view('dashboard', compact(
            'totalReceivables',
            'totalSales',
            'totalExpenses',
            'cashInHand',
            'recentSales',
            'expensesByCategory',
            'monthlyStats',
            'filter',
            'startDateStr',
            'endDateStr',
            'label'
        ));
    }
}
