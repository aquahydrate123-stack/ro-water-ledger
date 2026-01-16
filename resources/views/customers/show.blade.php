<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Customer Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-3">
                        <strong>Name:</strong> {{ $customer->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Email:</strong> {{ $customer->email }}
                    </div>
                    <div class="mb-3">
                        <strong>Phone:</strong> {{ $customer->phone }}
                    </div>
                    <div class="mb-3">
                        <strong>Address:</strong> {{ $customer->address }}
                    </div>
                    <div class="mb-3">
                        <strong>Status:</strong> {{ ucfirst($customer->status) }}
                    </div>
                    <div class="mb-3">
                        <strong>Created By:</strong> {{ $customer->user->name }}
                    </div>
                    <a href="{{ route('customers.edit', $customer) }}" class="btn btn-sm btn-info">Edit</a>
                    <a href="{{ route('customers.index') }}" class="btn btn-secondary">Back to List</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>




