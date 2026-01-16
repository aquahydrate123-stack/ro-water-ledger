<x-app-layout>
    <div class="min-h-screen bg-white pb-32 font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 print:w-full print:max-w-none print:px-0 relative pt-24">

            <!-- Top Filter Bar & Buttons -->
            <form action="{{ route('customers.ledger', $customer) }}" method="GET" class="mb-8 no-print pt-6">
                <div class="flex w-full items-end gap-4">
                    <div class="flex-1">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">Start
                            Date</label>
                        <div class="relative">
                            <input type="date" name="from_date" value="{{ $fromDate }}"
                                class="w-full h-10 rounded-lg border-slate-300 text-slate-700 font-bold focus:border-cyan-500 focus:ring-cyan-500">
                        </div>
                    </div>
                    <div class="flex-1">
                        <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-1">End
                            Date</label>
                        <div class="relative">
                            <input type="date" name="to_date" value="{{ $toDate }}"
                                class="w-full h-10 rounded-lg border-slate-300 text-slate-700 font-bold focus:border-cyan-500 focus:ring-cyan-500">
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit"
                            class="h-10 px-6 bg-cyan-600 hover:bg-cyan-700 text-white font-bold text-xs uppercase tracking-widest rounded-lg shadow-md transition whitespace-nowrap">
                            Update
                        </button>
                        <a href="{{ route('customers.index') }}"
                            class="h-10 px-6 flex items-center justify-center bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold text-xs uppercase tracking-widest rounded-lg transition whitespace-nowrap">
                            Back
                        </a>
                    </div>
                </div>
            </form>

            <!-- Main Content Area -->
            <div class="bg-white">

                <!-- Branding & Customer Header -->
                <div class="py-8 border-b border-slate-100">
                    <div class="mb-6">
                        <h1 class="text-xl font-bold text-cyan-600 uppercase tracking-tight">Aqua Hydrate</h1>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Premium RO Water
                            Ledger System</p>
                    </div>

                    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                        <div>
                            <span
                                class="inline-block px-4 py-1.5 rounded-full bg-cyan-600 text-white text-[10px] font-black uppercase tracking-widest mb-3">
                                Authorized Statement
                            </span>
                            <h2 class="text-4xl font-black text-slate-800">{{ $customer->name }}</h2>
                            <p class="text-slate-500 font-bold mt-1 text-lg">{{ $customer->phone }}</p>
                            @if($customer->address)
                                <p class="text-slate-400 text-xs mt-1">{{ $customer->address }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Summary Cards Row (Matching Screenshot) -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 py-8">
                    <!-- Opening -->
                    <div class="p-5 border border-slate-200 rounded-lg bg-white">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Opening</p>
                        <p class="text-xl font-bold text-slate-700">Rs. {{ number_format($openingBalance, 2) }}</p>
                    </div>

                    <!-- Periodic Sales -->
                    <div class="p-5 border border-slate-200 rounded-lg bg-white">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Periodic Sales
                        </p>
                        <p class="text-xl font-bold text-slate-700">Rs. {{ number_format($periodSales, 2) }}</p>
                    </div>

                    <!-- Periodic Paid -->
                    <div class="p-5 border border-slate-200 rounded-lg bg-white">
                        <p class="text-[10px] font-bold text-emerald-500 uppercase tracking-widest mb-1">Periodic Paid
                        </p>
                        @php
                            $totalPayments = $transactions->sum('credit');
                        @endphp
                        <p class="text-xl font-bold text-emerald-600">Rs. {{ number_format($totalPayments, 2) }}</p>
                    </div>

                    <!-- Statement Balance (Highlighted Card) -->
                    <div class="p-5 rounded-lg bg-cyan-600 text-white shadow-lg">
                        <p class="text-[10px] font-black text-white/80 uppercase tracking-widest mb-1 opacity-90">
                            Statement Balance</p>
                        <p class="text-2xl font-black text-white">Rs. {{ number_format($closingBalance, 2) }}</p>
                    </div>
                </div>

                <!-- Ledger Table -->
                <div class="mt-4">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-slate-200">
                                <th
                                    class="py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Date</th>
                                <th
                                    class="py-4 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest w-1/3">
                                    Description</th>
                                <th
                                    class="py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Debit</th>
                                <th
                                    class="py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Credit</th>
                                <th
                                    class="py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Balance</th>
                                <th
                                    class="py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest no-print">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            <!-- Opening Balance Row -->
                            <tr class="border-b border-slate-100">
                                <td class="py-4 text-slate-400 font-bold text-xs">
                                    {{ \Carbon\Carbon::parse($fromDate)->format('d M y') }}
                                </td>
                                <td
                                    class="py-4 text-center font-black text-slate-300 uppercase tracking-widest text-xs">
                                    Balance B/F</td>
                                <td></td>
                                <td></td>
                                <td class="py-4 text-right font-black text-slate-700">
                                    {{ number_format($openingBalance, 2) }}
                                </td>
                                <td class="no-print"></td>
                            </tr>

                            @php $currentBalance = $openingBalance; @endphp
                            @foreach ($transactions as $t)
                                @php $currentBalance += $t->debit - $t->credit; @endphp
                                <tr class="border-b border-slate-100 hover:bg-slate-50 transition group">
                                    <td class="py-4 font-bold text-slate-600 text-xs">
                                        {{ \Carbon\Carbon::parse($t->date)->format('d M, Y') }}
                                    </td>
                                    <td class="py-4">
                                        <div class="flex items-center gap-3">
                                            <span class="font-bold text-slate-800 uppercase">{{ $t->reference }}</span>
                                            <span
                                                class="px-1.5 py-0.5 rounded text-[9px] font-black uppercase border 
                                                            {{ $t->type === 'sale' ? 'border-amber-200 text-amber-600 bg-amber-50' : 'border-cyan-200 text-cyan-600 bg-cyan-50' }}">
                                                {{ $t->type }}
                                            </span>
                                        </div>
                                    </td>
                                    <td
                                        class="py-4 text-right font-bold {{ $t->debit > 0 ? 'text-slate-800' : 'text-slate-300' }}">
                                        {{ $t->debit > 0 ? number_format($t->debit, 2) : '-' }}
                                    </td>
                                    <td
                                        class="py-4 text-right font-bold {{ $t->credit > 0 ? 'text-emerald-600' : 'text-slate-300' }}">
                                        {{ $t->credit > 0 ? number_format($t->credit, 2) : '-' }}
                                    </td>
                                    <td class="py-4 text-right font-black text-slate-800">
                                        {{ number_format($currentBalance, 2) }}
                                    </td>
                                    <td class="py-4 text-right no-print">
                                        @php
                                            $parts = explode('_', $t->id);
                                            $type = $parts[0];
                                            $id = end($parts);
                                        @endphp
                                        @if ($type === 'sale' && count($parts) === 2)
                                            <div class="flex justify-end gap-3 text-[10px] font-black uppercase">
                                                <a href="{{ route('sales.edit', $id) }}"
                                                    class="text-blue-500 hover:underline">Edit</a>
                                                <form action="{{ route('sales.destroy', $id) }}" method="POST"
                                                    onsubmit="return confirm('Delete?');" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button class="text-red-500 hover:underline">Del</button>
                                                </form>
                                            </div>
                                        @elseif ($type === 'payment')
                                            <div class="flex justify-end gap-3 text-[10px] font-black uppercase">
                                                <a href="{{ route('payments.edit', $id) }}"
                                                    class="text-blue-500 hover:underline">Edit</a>
                                                <form action="{{ route('payments.destroy', $id) }}" method="POST"
                                                    onsubmit="return confirm('Delete?');" class="inline">
                                                    @csrf @method('DELETE')
                                                    <button class="text-red-500 hover:underline">Del</button>
                                                </form>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Footer Bar (As per screenshot) -->
        <div class="fixed bottom-0 w-full bg-slate-900 text-white py-6 z-50 print:relative print:bg-slate-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 mb-1">Statement Ending
                        Balance</p>
                    <p class="text-3xl font-black text-white tracking-tight">Rs. {{ number_format($closingBalance, 2) }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 mb-1">Generated On</p>
                    <p class="text-xs font-bold text-slate-400 uppercase">
                        {{ \Carbon\Carbon::now()->format('d M, Y h:i A') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>