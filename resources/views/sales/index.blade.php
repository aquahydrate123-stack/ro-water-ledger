<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight uppercase tracking-wider">
                {{ __('Sales') }}
            </h2>
            <a href="{{ route('sales.create') }}"
                class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:from-blue-700 hover:to-indigo-700 shadow-lg transform hover:scale-105 transition ease-in-out duration-150">
                + New Sale
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Search Bar -->
                    <div class="mb-6">
                        <form action="{{ route('sales.index') }}" method="GET" class="flex gap-4">
                            <div class="flex-1">
                                <x-text-input name="search" placeholder="Search by customer, invoice #, or type..."
                                    class="w-full" :value="request('search')" />
                            </div>
                            <x-primary-button type="submit">
                                {{ __('Search') }}
                            </x-primary-button>
                            @if(request('search'))
                                <a href="{{ route('sales.index') }}"
                                    class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    {{ __('Clear') }}
                                </a>
                            @endif
                        </form>
                    </div>

                    <div class="overflow-x-auto rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead
                                class="bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 text-gray-600 dark:text-gray-300">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-extra-bold uppercase tracking-wider">
                                        Date</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-extra-bold uppercase tracking-wider">
                                        Customer</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-extra-bold uppercase tracking-wider">
                                        Type</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-extra-bold uppercase tracking-wider">
                                        Payment</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-extra-bold uppercase tracking-wider">
                                        Details</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-right text-xs font-extra-bold uppercase tracking-wider">
                                        Total</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-center text-xs font-extra-bold uppercase tracking-wider">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($sales as $sale)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700 dark:text-gray-200">
                                            {{ ($sale->sale_date ?? $sale->created_at)->format('M d, Y') }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                                            {{ $sale->customer ? $sale->customer->name : 'Walk-in' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $sale->sale_type === 'delivery' ? 'bg-blue-100 text-blue-800' : 'bg-cyan-100 text-cyan-800' }}">
                                                {{ strtoupper($sale->sale_type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $sale->payment_type === 'cash' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ strtoupper($sale->payment_type) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $sale->quantity }} x <span class="font-mono">{{ $sale->rate }}</span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-extrabold text-gray-900 dark:text-white">
                                            {{ number_format($sale->total_amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <a href="{{ route('sales.edit', $sale) }}"
                                                class="text-indigo-600 hover:text-indigo-900 dark:hover:text-indigo-400 font-bold uppercase text-xs border border-indigo-200 dark:border-indigo-700 px-3 py-1 rounded hover:bg-indigo-50 dark:hover:bg-indigo-900 transition mb-1 inline-block">
                                                Edit
                                            </a>
                                            @if ($sale->customer && $sale->customer->phone && $sale->invoice)
                                                @php
                                                    $phone = preg_replace('/[^0-9]/', '', $sale->customer->phone);
                                                    $message = urlencode("Dear {$sale->customer->name}, here is your Invoice #{$sale->invoice->invoice_number} for Amount {$sale->total_amount}. Date: " . ($sale->sale_date ?? $sale->created_at)->format('Y-m-d'));
                                                    $waLink = "https://wa.me/{$phone}?text={$message}";
                                                @endphp
                                                <a href="{{ $waLink }}" target="_blank"
                                                    class="ml-2 text-green-600 hover:text-green-900 dark:hover:text-green-400 font-bold uppercase text-xs border border-green-200 dark:border-green-700 px-3 py-1 rounded hover:bg-green-50 dark:hover:bg-green-900 transition mb-1 inline-block">
                                                    WhatsApp
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7"
                                            class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center font-medium">
                                            No sales recorded yet. <br>
                                            <a href="{{ route('sales.create') }}"
                                                class="text-indigo-600 hover:underline mt-2 inline-block">Create your first
                                                sale</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>