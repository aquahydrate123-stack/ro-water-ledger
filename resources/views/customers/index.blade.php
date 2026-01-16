<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight uppercase tracking-wider">
                {{ __('Customers') }}
            </h2>
            <a href="{{ route('customers.create') }}"
                class="px-6 py-3 bg-gradient-to-r from-cyan-600 to-teal-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:from-cyan-700 hover:to-teal-700 shadow-lg transform hover:scale-105 transition ease-in-out duration-150">
                + New Customer
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-xl">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if (session('success'))
                        <div class="mb-6 p-4 text-sm font-bold text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800 border-l-4 border-green-500"
                            role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Search Bar -->
                    <div class="mb-6">
                        <form action="{{ route('customers.index') }}" method="GET" class="flex gap-4">
                            <div class="flex-1">
                                <x-text-input name="search" placeholder="Search by name or phone..." class="w-full"
                                    :value="request('search')" />
                            </div>
                            <x-primary-button type="submit">
                                {{ __('Search') }}
                            </x-primary-button>
                            @if(request('search'))
                                <a href="{{ route('customers.index') }}"
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
                                        Name</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-extra-bold uppercase tracking-wider">
                                        Phone</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-extra-bold uppercase tracking-wider">
                                        Address</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-left text-xs font-extra-bold uppercase tracking-wider">
                                        Status</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-right text-xs font-extra-bold uppercase tracking-wider">
                                        Current Balance</th>
                                    <th scope="col"
                                        class="px-6 py-4 text-center text-xs font-extra-bold uppercase tracking-wider">
                                        Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($customers as $customer)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 dark:text-white">
                                            {{ $customer->name }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-600 dark:text-gray-300">
                                            {{ $customer->phone }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                            {{ Str::limit($customer->address, 30) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full {{ $customer->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ strtoupper($customer->status) }}
                                            </span>
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-right text-sm font-extrabold {{ $customer->balance > 0 ? 'text-red-600' : 'text-green-600' }}">
                                            {{ number_format($customer->balance, 2) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                            <div class="flex items-center justify-center gap-3">
                                                <a href="{{ route('customers.ledger', $customer) }}"
                                                    class="inline-flex items-center px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-black uppercase tracking-widest rounded-lg shadow-md hover:shadow-lg transition-all duration-200 transform hover:scale-105 group">
                                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                    </svg>
                                                    Ledger
                                                </a>
                                                <a href="{{ route('customers.edit', $customer) }}"
                                                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 font-bold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 rounded-lg transition ease-in-out duration-150">
                                                    Edit
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6"
                                            class="px-6 py-8 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center font-medium">
                                            No customers found. <br>
                                            <a href="{{ route('customers.create') }}"
                                                class="text-cyan-600 hover:underline mt-2 inline-block">Add your first
                                                customer</a>
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