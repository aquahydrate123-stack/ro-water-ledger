<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight uppercase tracking-wider">
                {{ $expenseCategory->name }} Ledger
            </h2>
            <a href="{{ route('expense-categories.index') }}"
                class="px-6 py-3 bg-gray-500 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-gray-600 shadow-lg transition ease-in-out duration-150">
                Back to Categories
            </a>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900/50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Filters -->
            <div
                class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl mb-8 border-b-4 border-indigo-500">
                <div class="p-6">
                    <form action="{{ route('expenses.ledger', $expenseCategory) }}" method="GET"
                        class="flex flex-wrap items-end gap-6">
                        <div class="min-w-[200px]">
                            <x-input-label for="filter" :value="__('Range Filter')"
                                class="text-xs font-bold uppercase text-gray-500" />
                            <select id="filter" name="filter" onchange="this.form.submit()"
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm font-semibold">
                                <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All Time</option>
                                <option value="daily" {{ $filter == 'daily' ? 'selected' : '' }}>Today</option>
                                <option value="monthly" {{ $filter == 'monthly' ? 'selected' : '' }}>This Month</option>
                                <option value="yearly" {{ $filter == 'yearly' ? 'selected' : '' }}>This Year</option>
                                <option value="custom" {{ $filter == 'custom' ? 'selected' : '' }}>Custom Period</option>
                            </select>
                        </div>

                        @if($filter == 'custom')
                            <div class="flex gap-4">
                                <div>
                                    <x-input-label for="start_date" :value="__('From')"
                                        class="text-xs font-bold uppercase text-gray-500" />
                                    <x-text-input id="start_date" name="start_date" type="date"
                                        class="mt-1 block w-full rounded-lg" :value="$startDate" />
                                </div>
                                <div>
                                    <x-input-label for="end_date" :value="__('To')"
                                        class="text-xs font-bold uppercase text-gray-500" />
                                    <x-text-input id="end_date" name="end_date" type="date"
                                        class="mt-1 block w-full rounded-lg" :value="$endDate" />
                                </div>
                                <div class="pb-1">
                                    <x-primary-button type="submit" class="bg-indigo-600 hover:bg-indigo-700 h-10">
                                        {{ __('Apply') }}
                                    </x-primary-button>
                                </div>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- Total Category Metrics Card -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
                <div
                    class="bg-white dark:bg-gray-800 shadow-2xl rounded-2xl p-8 border-l-8 border-red-500 transform transition">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-2 h-2 bg-red-500 rounded-full"></div>
                        <h3 class="text-xs font-black uppercase tracking-widest text-red-600">Total Expense for
                            {{ $expenseCategory->name }}</h3>
                    </div>
                    <div class="text-5xl font-black text-gray-900 dark:text-white">
                        <span class="text-xl font-bold text-gray-400">Rs.</span> {{ number_format($totalAmount, 2) }}
                    </div>
                    <div class="mt-6 flex items-center space-x-3 text-[10px] font-black uppercase tracking-tighter">
                        <span
                            class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 px-3 py-1 rounded-full">
                            Filter: {{ $filter }}
                        </span>
                        <span class="text-gray-400">
                            {{ $expenses->count() }} Transactions
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl">
                <div class="p-6 text-gray-900 dark:text-gray-100">
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
                                        Type</th>
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
                                @forelse ($expenses as $expense)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-700 dark:text-gray-200">
                                            {{ $expense->expense_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $expense->expense_type === 'business' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                                {{ strtoupper($expense->expense_type) }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-extrabold text-red-600 dark:text-red-400">
                                            Rs. {{ number_format($expense->amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $expense->notes ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <a href="{{ route('expenses.edit', $expense) }}"
                                                class="text-indigo-600 hover:text-indigo-900 dark:hover:text-indigo-400 font-bold uppercase text-xs border border-indigo-200 dark:border-indigo-700 px-3 py-1 rounded hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5"
                                            class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center font-medium">
                                            No records found for this period.
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