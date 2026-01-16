<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sale;
use App\Models\Payment;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::where('created_by', Auth::id());

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->get();
        return view('customers.index', compact('customers'));
    }

    public function ledger(Request $request, Customer $customer)
    {
        if ($customer->created_by !== Auth::id()) {
            abort(403);
        }

        $fromDate = $request->input('from_date', now()->startOfMonth()->format('Y-m-d'));
        $toDate = $request->input('to_date', now()->format('Y-m-d'));

        // 1. Calculate Opening Balance (Balance before fromDate)
        // We use whereDate for strict comparison
        $salesBefore = $customer->sales()
            ->where('payment_type', 'credit') // Only credit sales represent receivables
            ->where(function ($q) use ($fromDate) {
                $q->whereDate('sale_date', '<', $fromDate)
                    ->orWhere(function ($sq) use ($fromDate) {
                        $sq->whereNull('sale_date')->whereDate('created_at', '<', $fromDate);
                    });
            })
            ->sum('total_amount');

        $paymentsBefore = $customer->payments()
            ->whereDate('payment_date', '<', $fromDate)
            ->sum('amount');

        $openingBalance = (float) $salesBefore - (float) $paymentsBefore;

        // 2. Fetch Transactions within range (Attempt 5: Including all sales for double-entry visibility)
        $sales = $customer->sales()
            ->where(function ($q) use ($fromDate, $toDate) {
                $q->where(function ($sq) use ($fromDate, $toDate) {
                    $sq->whereDate('sale_date', '>=', $fromDate)
                        ->whereDate('sale_date', '<=', $toDate);
                })
                    ->orWhere(function ($sq) use ($fromDate, $toDate) {
                        $sq->whereNull('sale_date')
                            ->whereDate('created_at', '>=', $fromDate)
                            ->whereDate('created_at', '<=', $toDate);
                    });
            })
            ->with('invoice')
            ->get();

        $payments = $customer->payments()
            ->whereDate('payment_date', '>=', $fromDate)
            ->whereDate('payment_date', '<=', $toDate)
            ->get();

        // 3. Mapping with Double-Entry logic for Cash Sales
        $allMappedTransactions = collect();

        foreach ($sales as $item) {
            $date = $item->sale_date ?: $item->created_at;
            $carbonDate = \Carbon\Carbon::parse($date);

            // Row 1: The Sale (Debit)
            $allMappedTransactions->push((object) [
                'id' => 'sale_' . $item->id,
                'date' => $carbonDate->format('Y-m-d'),
                'sort_date' => $carbonDate->toDateTimeString() . '_1', // Ensure sale comes first
                'type' => 'Sale',
                'reference' => ($item->invoice ? $item->invoice->invoice_number : 'Sale') . ($item->payment_type === 'cash' ? ' (Cash)' : ''),
                'debit' => (float) $item->total_amount,
                'credit' => 0.0,
            ]);

            // Row 2: IF Cash, add an immediate offsetting Payment (Credit)
            if ($item->payment_type === 'cash') {
                $allMappedTransactions->push((object) [
                    'id' => 'sale_pay_' . $item->id,
                    'date' => $carbonDate->format('Y-m-d'),
                    'sort_date' => $carbonDate->toDateTimeString() . '_2', // Offset follows sale
                    'type' => 'Payment',
                    'reference' => 'Immediate Cash Payment',
                    'debit' => 0.0,
                    'credit' => (float) $item->total_amount,
                ]);
            }
        }

        foreach ($payments as $item) {
            $date = $item->payment_date ?: $item->created_at;
            $carbonDate = \Carbon\Carbon::parse($date);
            $allMappedTransactions->push((object) [
                'id' => 'payment_' . $item->id,
                'date' => $carbonDate->format('Y-m-d'),
                'sort_date' => $carbonDate->toDateTimeString() . '_3',
                'type' => 'Payment',
                'reference' => 'Payment Received',
                'debit' => 0.0,
                'credit' => (float) $item->amount,
            ]);
        }

        // 4. Sort and Finalize
        $transactions = $allMappedTransactions->sortBy('sort_date')->values();

        // 5. Calculate Statement Period Totals
        $periodSales = (float) $transactions->where('type', 'Sale')->sum('debit');
        $periodPaid = (float) $transactions->where('type', 'Payment')->sum('credit');
        $closingBalance = (float) $openingBalance + $periodSales - $periodPaid;

        return view('customers.ledger', compact(
            'customer',
            'transactions',
            'openingBalance',
            'periodSales',
            'periodPaid',
            'closingBalance',
            'fromDate',
            'toDate'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required|max:255',
            'address' => 'nullable|max:255',
            'email' => 'nullable|email|unique:customers,email',
            'status' => 'required|in:active,inactive',
        ]);

        Customer::create([
            ...$validated,
            'user_id' => Auth::id(),
            'created_by' => Auth::id(),
        ]);



        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $customer = Auth::user()->customers()->findOrFail($id);
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $customer = Auth::user()->customers()->findOrFail($id);
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer = Auth::user()->customers()->findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|max:255',
            'phone' => 'required|max:255',
            'address' => 'nullable|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'status' => 'required|in:active,inactive',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // $customer = Customer::findOrFail($id);
        // $customer->delete();
        // return redirect()->route('customers.index')
        //     ->with('success', 'Customer deleted successfully.');
    }
}
