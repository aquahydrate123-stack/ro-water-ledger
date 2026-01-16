<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->sales()->with(['customer', 'invoice']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('customer', function ($cq) use ($search) {
                    $cq->where('name', 'like', "%{$search}%");
                })->orWhereHas('invoice', function ($iq) use ($search) {
                    $iq->where('invoice_number', 'like', "%{$search}%");
                })->orWhere('sale_type', 'like', "%{$search}%")
                    ->orWhere('payment_type', 'like', "%{$search}%")
                    ->orWhere('total_amount', 'like', "%{$search}%");
            });
        }

        $sales = $query->latest()->get();
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $customers = Customer::where('created_by', Auth::id())
            ->where('status', 'active')
            ->get();

        return view('sales.create', compact('customers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'sale_type' => 'required|in:refill,delivery',
            'payment_type' => 'required|in:cash,credit',
            'quantity' => 'required|numeric|min:1',
            'rate' => 'required|numeric|min:0',
            'sale_date' => 'required|date',
        ];

        if ($request->sale_type === 'delivery') {
            $rules['customer_id'] = 'required|exists:customers,id';
            $rules['payment_type'] = 'required|in:cash,credit';
        } else { // refill
            $rules['customer_id'] = 'nullable|exists:customers,id';
            $rules['payment_type'] = 'required|in:cash';
        }

        $validatedData = $request->validate($rules);

        // Calculate total_amount
        $validatedData['total_amount'] = $validatedData['quantity'] * $validatedData['rate'];

        // Set created_by (user_id)
        $validatedData['user_id'] = Auth::id();

        // High robustness: Explicitly set customer_id from request if refill
        if ($request->sale_type === 'refill' && $request->filled('customer_id')) {
            $validatedData['customer_id'] = $request->customer_id;
        }

        $sale = new Sale($validatedData);
        $sale->user_id = Auth::id(); // Guardrails
        if (isset($validatedData['customer_id'])) {
            $sale->customer_id = $validatedData['customer_id'];
        }
        $sale->save();

        // Generate Invoice
        // Format: INV-YYYYMMDD-SALEID
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad($sale->id, 4, '0', STR_PAD_LEFT);

        if ($sale->customer_id) {
            \App\Models\Invoice::create([
                'sale_id' => $sale->id,
                'customer_id' => $sale->customer_id,
                'invoice_number' => $invoiceNumber,
                'invoice_date' => $sale->sale_date,
                'total_amount' => $sale->total_amount,
            ]);

            return redirect()->route('sales.index')
                ->with('success', 'Sale recorded and Invoice #' . $invoiceNumber . ' generated.');
        }

        return redirect()->route('sales.index')
            ->with('success', 'Sale recorded successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sale $sale)
    {
        if ($sale->user_id !== Auth::id()) {
            abort(403);
        }
        $customers = Auth::user()->customers()->where('status', 'active')->get();
        return view('sales.edit', compact('sale', 'customers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Sale $sale)
    {
        if ($sale->user_id !== Auth::id()) {
            abort(403);
        }

        $rules = [
            'sale_type' => 'required|in:refill,delivery',
            'payment_type' => 'required|in:cash,credit',
            'quantity' => 'required|numeric|min:1',
            'rate' => 'required|numeric|min:0',
            'sale_date' => 'required|date',
        ];

        if ($request->sale_type === 'delivery') {
            $rules['customer_id'] = 'required|exists:customers,id';
        } else {
            $rules['customer_id'] = 'nullable';
        }

        $validatedData = $request->validate($rules);

        $validatedData['total_amount'] = (float) $validatedData['quantity'] * (float) $validatedData['rate'];

        $sale->update($validatedData);

        // Update Invoice Date if sale date changed
        if ($sale->invoice) {
            $sale->invoice->update([
                'invoice_date' => $validatedData['sale_date'],
                'total_amount' => $validatedData['total_amount'],
            ]);
        }

        return redirect()->route('sales.index')
            ->with('success', 'Sale updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sale $sale)
    {
        if ($sale->user_id !== Auth::id()) {
            abort(403);
        }

        $sale->delete();

        return redirect()->route('sales.index')
            ->with('success', 'Sale deleted successfully.');
    }
}
