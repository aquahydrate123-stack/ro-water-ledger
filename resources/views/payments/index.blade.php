<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight uppercase tracking-wider">
                {{ __('Payments') }}
            </h2>
            <a href="{{ route('payments.create') }}"
                class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-green-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:from-emerald-600 hover:to-green-700 shadow-lg transform hover:scale-105 transition ease-in-out duration-150">
                + New Payment
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Search Bar -->
                    <div class="mb-6">
                        <form action="{{ route('payments.index') }}" method="GET" class="flex gap-4">
                            <div class="flex-1">
                                <x-text-input name="search" placeholder="Search by customer, amount, or notes..."
                                    class="w-full" :value="request('search')" />
                            </div>
                            <x-primary-button type="submit">
                                {{ __('Search') }}
                            </x-primary-button>
                            @if(request('search'))
                                <a href="{{ route('payments.index') }}"
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
                                        Amount</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-extra-bold uppercase tracking-wider">
                                        Notes</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-center text-xs font-extra-bold uppercase tracking-wider">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($payments as $payment)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700 dark:text-gray-200">
                                            {{ $payment->payment_date->format('M d, Y') }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                                            {{ $payment->customer->name }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-extrabold text-emerald-600 dark:text-emerald-400">
                                            {{ number_format($payment->amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ Str::limit($payment->notes, 40) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <a href="{{ route('payments.edit', $payment) }}"
                                                class="text-emerald-600 hover:text-emerald-900 dark:hover:text-emerald-400 font-bold uppercase text-xs border border-emerald-200 dark:border-emerald-700 px-3 py-1 rounded hover:bg-emerald-50 dark:hover:bg-emerald-900 transition">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"
                                            class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center font-medium">
                                            No payments recorded yet. <br>
                                            <a href="{{ route('payments.create') }}"
                                                class="text-emerald-600 hover:underline mt-2 inline-block">Record your first
                                                payment</a>
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