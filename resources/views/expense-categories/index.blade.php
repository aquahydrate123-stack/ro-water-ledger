<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight uppercase tracking-wider">
                {{ __('Expense Categories') }}
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('expenses.index') }}"
                    class="px-6 py-3 bg-gray-500 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-gray-600 shadow-lg transition ease-in-out duration-150">
                    Back to Expenses
                </a>
                <a href="{{ route('expense-categories.create') }}"
                    class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-500 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:from-blue-600 hover:to-indigo-600 shadow-lg transform hover:scale-105 transition ease-in-out duration-150">
                    + New Category
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="overflow-x-auto rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead
                                class="bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 text-gray-600 dark:text-gray-300">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-extra-bold uppercase tracking-wider">
                                        Name</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-extra-bold uppercase tracking-wider">
                                        Description</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-center text-xs font-extra-bold uppercase tracking-wider">
                                        Ledger</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-center text-xs font-extra-bold uppercase tracking-wider">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($categories as $category)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                                            {{ $category->name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ $category->description ?? 'No description' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <a href="{{ route('expenses.ledger', $category) }}"
                                                class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400 font-bold uppercase text-xs border border-blue-200 dark:border-blue-700 px-3 py-1 rounded hover:bg-blue-50 dark:hover:bg-blue-900 transition">
                                                View Ledger
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <div class="flex justify-center space-x-2">
                                                <a href="{{ route('expense-categories.edit', $category) }}"
                                                    class="text-indigo-600 hover:text-indigo-900 dark:hover:text-indigo-400 font-bold uppercase text-xs border border-indigo-200 dark:border-indigo-700 px-3 py-1 rounded hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
                                                    Edit
                                                </a>
                                                <form action="{{ route('expense-categories.destroy', $category) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Delete this category and all its expenses?');">
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
                                        <td colspan="4"
                                            class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center font-medium">
                                            No categories found.
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