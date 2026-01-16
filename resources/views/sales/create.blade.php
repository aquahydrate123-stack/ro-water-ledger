<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Create Sale') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('sales.store') }}" method="POST">
                        @csrf

                        <!-- Sale Type -->
                        <div class="mb-4">
                            <x-input-label for="sale_type" :value="__('Sale Type')" />
                            <select id="sale_type" name="sale_type"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                required>
                                <option value="">Select Sale Type</option>
                                <option value="refill" {{ old('sale_type') == 'refill' ? 'selected' : '' }}>Refill
                                </option>
                                <option value="delivery" {{ old('sale_type') == 'delivery' ? 'selected' : '' }}>Delivery
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('sale_type')" class="mt-2" />
                        </div>

                        <!-- Customer (Hidden by default) -->
                        <div class="mb-4" id="customer_id_field" style="display: none;">
                            <x-input-label for="customer_id" :value="__('Customer')" />
                            <select id="customer_id" name="customer_id"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Select Customer</option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>{{ $customer->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('customer_id')" class="mt-2" />
                        </div>

                        <!-- Payment Type -->
                        <div class="mb-4">
                            <x-input-label for="payment_type" :value="__('Payment Type')" />
                            <select id="payment_type" name="payment_type"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                required>
                                <option value="">Select Payment Type</option>
                                <option value="cash" {{ old('payment_type') == 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="credit" {{ old('payment_type') == 'credit' ? 'selected' : '' }}>Credit
                                </option>
                            </select>
                            <x-input-error :messages="$errors->get('payment_type')" class="mt-2" />
                        </div>

                        <!-- Sale Date -->
                        <div class="mb-4">
                            <x-input-label for="sale_date" :value="__('Sale Date')" />
                            <x-text-input id="sale_date" class="block mt-1 w-full" type="date" name="sale_date"
                                :value="old('sale_date', date('Y-m-d'))" required />
                            <x-input-error :messages="$errors->get('sale_date')" class="mt-2" />
                        </div>

                        <!-- Quantity -->
                        <div class="mb-4">
                            <x-input-label for="quantity" :value="__('Quantity')" />
                            <x-text-input id="quantity" class="block mt-1 w-full" type="number" name="quantity"
                                :value="old('quantity')" required min="1" />
                            <x-input-error :messages="$errors->get('quantity')" class="mt-2" />
                        </div>

                        <!-- Rate -->
                        <div class="mb-4">
                            <x-input-label for="rate" :value="__('Rate')" />
                            <x-text-input id="rate" class="block mt-1 w-full" type="number" step="0.01" name="rate"
                                :value="old('rate')" required min="0" />
                            <x-input-error :messages="$errors->get('rate')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('sales.index') }}"
                                class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white mr-4">Cancel</a>
                            <x-primary-button>
                                {{ __('Create Sale') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Basic JavaScript for conditional display of customer dropdown and payment type --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const saleType = document.getElementById('sale_type');
            const customerIdField = document.getElementById('customer_id_field');
            const customerIdSelect = document.getElementById('customer_id');
            const paymentTypeSelect = document.getElementById('payment_type');

            function toggleFields() {
                if (saleType.value === 'delivery') {
                    customerIdField.style.display = 'block';
                    customerIdSelect.setAttribute('required', 'required');

                    paymentTypeSelect.innerHTML = '<option value="">Select Payment Type</option><option value="cash">Cash</option><option value="credit">Credit</option>';
                    @if(old('payment_type'))
                        paymentTypeSelect.value = "{{ old('payment_type') }}";
                    @endif
                } else if (saleType.value === 'refill') {
                    // Refills can now have an optional customer
                    customerIdField.style.display = 'block';
                    customerIdSelect.removeAttribute('required');
                    
                    paymentTypeSelect.innerHTML = '<option value="cash">Cash</option>';
                    paymentTypeSelect.value = 'cash';
                } else {
                    customerIdField.style.display = 'none';
                    customerIdSelect.removeAttribute('required');
                    customerIdSelect.value = '';
                    paymentTypeSelect.innerHTML = '<option value="">Select Payment Type</option><option value="cash">Cash</option><option value="credit">Credit</option>';
                }
            }

            saleType.addEventListener('change', toggleFields);

            // Initial call
            if (saleType.value) {
                toggleFields();
            }
        });
    </script>
</x-app-layout>