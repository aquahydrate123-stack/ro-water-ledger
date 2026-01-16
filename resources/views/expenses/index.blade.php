<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Expenses') }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('expense-categories.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Manage Categories') }}
                </a>
                <a href="{{ route('expenses.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-emerald-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-emerald-700 focus:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Add Expense') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Search Bar -->
                    <div class="mb-6">
                        <form action="{{ route('expenses.index') }}" method="GET" class="flex gap-4">
                            <div class="flex-1">
                                <x-text-input name="search" placeholder="Search by category, amount, or notes..."
                                    class="w-full" :value="request('search')" />
                            </div>
                            <x-primary-button type="submit">
                                {{ __('Search') }}
                            </x-primary-button>
                            @if(request('search'))
                                <a href="{{ route('expenses.index') }}"
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
                                        Category</th>
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
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                                            {{ $expense->category->name ?? 'N/A' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $expense->expense_type === 'business' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                                {{ strtoupper($expense->expense_type) }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-extrabold text-red-600 dark:text-red-400">
                                            {{ number_format($expense->amount, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ Str::limit($expense->notes, 30) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <div class="flex justify-center space-x-2">
                                                <a href="{{ route('expenses.edit', $expense) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 dark:hover:text-indigo-400 font-bold uppercase text-xs border border-indigo-200 dark:border-indigo-700 px-3 py-1 rounded hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
                                                    Edit
                                                </a>
                                                <form action="{{ route('expenses.destroy', $expense) }}" method="POST"
                                                    onsubmit="return confirm('Delete this expense?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 dark:hover:text-red-400 font-bold uppercase text-xs border border-red-200 dark:border-red-700 px-3 py-1 rounded hover:bg-red-50 dark:hover:bg-red-900 transition">
                                                        Del
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"
                                            class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center font-medium">
                                            No expenses recorded yet. <br>
                                            <a href="{{ route('expenses.create') }}"
                                                class="text-orange-600 hover:underline mt-2 inline-block">Record your first
                                                expense</a>
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