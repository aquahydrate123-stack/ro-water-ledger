<x-app-layout>
    <x-slot name="header">
        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8 flex justify-between items-end">
            <div class="flex flex-col">
                <h2 class="text-3xl font-black tracking-tight"
                    style="background: linear-gradient(to right, #2563eb, #9333ea); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                    AQUA HYDRATE</h2>
            </div>

            <!-- Global Privacy Toggle -->
            <div x-data class="flex items-center space-x-2">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Privacy Mode</span>
                <button @click="$dispatch('toggle-privacy')"
                    class="text-slate-400 hover:text-blue-500 transition-colors focus:outline-none">
                    <i class="fas fa-eye text-sm"></i>
                </button>
            </div>
        </div>
    </x-slot>

    <div style="background-color: #0f172a !important; min-height: 100vh;" class="py-8" x-data="{ showValues: false }"
        @toggle-privacy.window="showValues = !showValues">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Quick Actions (Top Row - Below Header) -->
            <div class="grid grid-cols-3 gap-4 mb-2">
                <a href="{{ route('sales.create') }}"
                    class="group relative overflow-hidden rounded-lg bg-blue-600 shadow-lg shadow-blue-500/20 transition-all hover:scale-[1.02] hover:shadow-blue-500/30">
                    <div
                        class="flex items-center justify-center space-x-2 bg-blue-600 px-4 py-2 transition-colors group-hover:bg-blue-500 h-full">
                        <i class="fas fa-plus text-xs text-blue-100"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-white">Sale</span>
                    </div>
                </a>

                <a href="{{ route('expenses.create') }}"
                    class="group relative overflow-hidden rounded-lg bg-slate-800 border border-slate-700 shadow-sm transition-all hover:scale-[1.02]"
                    style="background-color: #1e293b; border-color: #334155;">
                    <div
                        class="flex items-center justify-center space-x-2 px-4 py-2 transition-colors group-hover:bg-slate-700 h-full">
                        <i class="fas fa-file-invoice-dollar text-xs text-slate-400 group-hover:text-slate-200"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-300">Expense</span>
                    </div>
                </a>

                <a href="{{ route('customers.create') }}"
                    class="group relative overflow-hidden rounded-lg bg-slate-800 border border-slate-700 shadow-sm transition-all hover:scale-[1.02]"
                    style="background-color: #1e293b; border-color: #334155;">
                    <div
                        class="flex items-center justify-center space-x-2 px-4 py-2 transition-colors group-hover:bg-slate-700 h-full">
                        <i class="fas fa-user-plus text-xs text-slate-400 group-hover:text-slate-200"></i>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-300">Client</span>
                    </div>
                </a>
            </div>

            <!-- Filter Controls -->
            <div style="background-color: #1e293b; border-color: #334155;"
                class="flex flex-col sm:flex-row justify-between items-center p-4 rounded-xl border shadow-sm mb-8 space-y-4 sm:space-y-0">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wide">Data Filter</span>
                <form action="{{ route('dashboard') }}" method="GET" class="flex flex-wrap items-center gap-3">
                    <select name="filter" onchange="this.form.submit()"
                        class="text-xs font-semibold border-none rounded-lg py-2 pl-4 pr-10 text-slate-300 focus:ring-2 focus:ring-blue-500 cursor-pointer transition-colors"
                        style="background-color: #334155;">
                        <option value="daily" {{ $filter == 'daily' ? 'selected' : '' }}>Today</option>
                        <option value="monthly" {{ $filter == 'monthly' ? 'selected' : '' }}>This Month</option>
                        <option value="last_month" {{ $filter == 'last_month' ? 'selected' : '' }}>Last Month</option>
                        <option value="yearly" {{ $filter == 'yearly' ? 'selected' : '' }}>This Year</option>
                        <option value="last_year" {{ $filter == 'last_year' ? 'selected' : '' }}>Last Year</option>
                        <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All Time</option>
                        <option value="custom" {{ $filter == 'custom' ? 'selected' : '' }}>Custom Period</option>
                    </select>

                    @if($filter === 'custom')
                        <div class="flex items-center space-x-2">
                            <input type="date" name="start_date" value="{{ $startDateStr }}"
                                class="text-xs font-semibold border-none rounded-lg py-1.5 px-3 text-slate-300 focus:ring-2 focus:ring-blue-500"
                                style="background-color: #334155;">
                            <span class="text-slate-500 text-xs">to</span>
                            <input type="date" name="end_date" value="{{ $endDateStr }}"
                                class="text-xs font-semibold border-none rounded-lg py-1.5 px-3 text-slate-300 focus:ring-2 focus:ring-blue-500"
                                style="background-color: #334155;">
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-500 text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-lg transition-colors">
                                Apply
                            </button>
                        </div>
                    @endif
                </form>
            </div>

            <div style="height: 1rem;"></div>

            <!-- 1. Summary Cards Section (V2 - Match Screenshot - INLINE STYLES) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- Card 1: Total Receivables (Red Theme) -->
                <div style="background-color: #FFF5F5; border-left: 8px solid #EF4444;"
                    class="rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow cursor-pointer relative overflow-hidden group"
                    @click="showValues = !showValues">
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <span style="color: #EF4444;"
                            class="text-[11px] font-black uppercase tracking-widest mb-3">Total Receivables
                            ({{ $label }})</span>
                        <div style="color: #1e293b;" class="text-[32px] font-black tracking-tighter" x-show="showValues"
                            x-transition.opacity>
                            {{ number_format($totalReceivables, 2) }}
                        </div>
                        <div style="color: #1e293b;" class="text-[32px] font-black tracking-tighter select-none"
                            x-show="!showValues">
                            •••••••
                        </div>
                    </div>
                </div>

                <!-- Card 2: Sales (Cyan Theme) -->
                <div style="background-color: #F0FDFA; border-left: 8px solid #06B6D4;"
                    class="rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow cursor-pointer relative overflow-hidden group"
                    @click="showValues = !showValues">
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <span style="color: #06B6D4;"
                            class="text-[11px] font-black uppercase tracking-widest mb-3">{{ $label }}'s Sales</span>
                        <div style="color: #1e293b;" class="text-[32px] font-black tracking-tighter" x-show="showValues"
                            x-transition.opacity>
                            {{ number_format($totalSales, 2) }}
                        </div>
                        <div style="color: #1e293b;" class="text-[32px] font-black tracking-tighter select-none"
                            x-show="!showValues">
                            •••••••
                        </div>
                    </div>
                </div>

                <!-- Card 3: Expenses (Orange Theme) -->
                <div style="background-color: #FFF7ED; border-left: 8px solid #F97316;"
                    class="rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow cursor-pointer relative overflow-hidden group"
                    @click="showValues = !showValues">
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <span style="color: #F97316;"
                            class="text-[11px] font-black uppercase tracking-widest mb-3">{{ $label }}'s Expenses</span>
                        <div style="color: #1e293b;" class="text-[32px] font-black tracking-tighter" x-show="showValues"
                            x-transition.opacity>
                            {{ number_format($totalExpenses, 2) }}
                        </div>
                        <div style="color: #1e293b;" class="text-[32px] font-black tracking-tighter select-none"
                            x-show="!showValues">
                            •••••••
                        </div>
                    </div>
                </div>

                <!-- Card 4: Net Cash (Green Theme) -->
                <div style="background-color: #F0FDF4; border-left: 8px solid #22C55E;"
                    class="rounded-xl p-6 shadow-sm hover:shadow-md transition-shadow cursor-pointer relative overflow-hidden group"
                    @click="showValues = !showValues">
                    <div class="relative z-10 flex flex-col justify-between h-full">
                        <span style="color: #22C55E;" class="text-[11px] font-black uppercase tracking-widest mb-3">Net
                            Cash ({{ $label }})</span>
                        <div style="color: #1e293b;" class="text-[32px] font-black tracking-tighter" x-show="showValues"
                            x-transition.opacity>
                            {{ number_format($cashInHand, 2) }}
                        </div>
                        <div style="color: #1e293b;" class="text-[32px] font-black tracking-tighter select-none"
                            x-show="!showValues">
                            •••••••
                        </div>
                    </div>
                </div>

                <!-- End of Summary Cards Grid -->
            </div>
            <!-- Spacer between Cards and Transactions -->
            <div style="height: 3rem;"></div>

            <!-- 3. Recent Transactions -->
            <div style="background-color: #1e293b; border-color: #334155;"
                class="mb-8 rounded-2xl border shadow-sm overflow-hidden">
                <div style="background-color: #334155; border-color: #475569;"
                    class="px-6 py-4 border-b flex justify-between items-center">
                    <h3 class="text-xs font-bold text-slate-300 uppercase tracking-widest">Recent Transactions</h3>
                    <a href="{{ route('sales.index') }}"
                        class="text-[10px] font-bold text-blue-400 hover:text-blue-300 uppercase tracking-wider transition-colors">View
                        All &rarr;</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-700">
                        <thead>
                            <tr style="background-color: #1e293b;">
                                <th
                                    class="px-6 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    Date</th>
                                <th
                                    class="px-6 py-3 text-left text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    Entity</th>
                                <th
                                    class="px-6 py-3 text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    Type</th>
                                <th
                                    class="px-6 py-3 text-right text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                    Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-700">
                            @forelse ($recentSales as $sale)
                                <tr class="hover:bg-slate-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-xs font-semibold text-slate-400">
                                        {{ $sale->sale_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-white">
                                        {{ $sale->customer ? $sale->customer->name : 'Walk-in Customer' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide {{ $sale->sale_type === 'delivery' ? 'bg-blue-900/50 text-blue-200' : 'bg-cyan-900/50 text-cyan-200' }}">
                                            {{ $sale->sale_type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-bold text-white">
                                        <span x-show="showValues">{{ number_format($sale->total_amount, 2) }}</span>
                                        <span x-show="!showValues" class="text-slate-500">••••••</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4"
                                        class="px-6 py-12 text-center text-slate-500 text-xs uppercase tracking-widest font-bold opacity-60">
                                        No recent activity
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>





            <!-- 2. Analytical Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <!-- Trend Chart -->
                <div style="background-color: #1e293b; border-color: #334155;"
                    class="lg:col-span-8 p-6 rounded-2xl border shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Revenue Flow</h4>
                        <span class="text-[10px] font-bold text-slate-300 px-2 py-1 rounded"
                            style="background-color: #334155;">Monthly</span>
                    </div>
                    <div class="h-[320px]">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>

                <!-- Expense Split -->
                <div style="background-color: #1e293b; border-color: #334155;"
                    class="lg:col-span-4 p-6 rounded-2xl border shadow-sm flex flex-col">
                    <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Cost Distribution</h4>
                    <div class="relative flex-grow min-h-[200px]">
                        <canvas id="expenseChart"></canvas>
                    </div>
                    <div class="mt-6 space-y-3">
                        @foreach($expensesByCategory->take(4) as $index => $expense)
                            <div
                                class="flex items-center justify-between text-xs font-medium border-b border-slate-700 pb-2 last:border-0">
                                <div class="flex items-center">
                                    <div class="w-2.5 h-2.5 rounded-sm mr-3"
                                        style="background-color: {{ ['#3b82f6', '#ef4444', '#f59e0b', '#10b981', '#6366f1', '#ec4899', '#8b5cf6'][$index] ?? '#cbd5e1' }}">
                                    </div>
                                    <span class="text-slate-300"
                                        style="color: #cbd5e1;">{{ Str::limit($expense->name, 20) }}</span>
                                </div>
                                <span class="text-white font-bold" x-show="showValues" style="color: #ffffff;"
                                    x-transition>{{ number_format($expense->total, 0) }}</span>
                                <span class="text-slate-500 font-bold" x-show="!showValues"
                                    style="color: #64748b;">•••</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>



        </div>
    </div>

    @push('scripts')
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Hardcoded Dark Mode Colors since we are forcing dark theme
                const textColor = '#94a3b8';
                const gridColor = '#334155';
                const isDark = true;

                // Trend Matrix
                const trendCtx = document.getElementById('trendChart').getContext('2d');
                new Chart(trendCtx, {
                    type: 'bar',
                    data: {
                        labels: @json(collect($monthlyStats)->map(fn($s) => $s['month'])),
                        datasets: [
                            {
                                label: 'Inflow',
                                data: @json(collect($monthlyStats)->map(fn($s) => $s['sales'])),
                                backgroundColor: '#3b82f6',
                                hoverBackgroundColor: '#2563eb',
                                borderRadius: 6,
                                barThickness: 20
                            },
                            {
                                label: 'Outflow',
                                data: @json(collect($monthlyStats)->map(fn($s) => $s['expenses'])),
                                backgroundColor: '#ef4444',
                                hoverBackgroundColor: '#dc2626',
                                borderRadius: 6,
                                barThickness: 20
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                                align: 'end',
                                labels: {
                                    usePointStyle: true,
                                    boxWidth: 8,
                                    font: { family: "'Figtree', sans-serif", weight: 'bold', size: 11 },
                                    color: textColor
                                }
                            },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleColor: '#ffffff',
                                bodyColor: '#cbd5e1',
                                borderColor: '#334155',
                                borderWidth: 1,
                                padding: 10,
                                cornerRadius: 8,
                                displayColors: true
                            }
                        },
                        scales: {
                            y: {
                                grid: { color: gridColor, borderDash: [4, 4] },
                                ticks: { font: { size: 10, family: "'Figtree', sans-serif" }, color: textColor },
                                border: { display: false }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { font: { size: 10, family: "'Figtree', sans-serif" }, color: textColor },
                                border: { display: false }
                            }
                        },
                        animation: {
                            duration: 1500,
                            easing: 'easeOutQuart'
                        }
                    }
                });

                // Expense Split
                const expenseCtx = document.getElementById('expenseChart').getContext('2d');
                new Chart(expenseCtx, {
                    type: 'doughnut',
                    data: {
                        labels: @json($expensesByCategory->map(fn($e) => $e->name)),
                        datasets: [{
                            data: @json($expensesByCategory->map(fn($e) => $e->total)),
                            backgroundColor: ['#3b82f6', '#ef4444', '#f59e0b', '#10b981', '#6366f1', '#ec4899'],
                            borderWidth: 2,
                            borderColor: '#1e293b',
                            hoverOffset: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: '#1e293b',
                                titleColor: '#ffffff',
                                bodyColor: '#cbd5e1',
                                borderColor: '#334155',
                                borderWidth: 1,
                                padding: 10,
                                cornerRadius: 8
                            }
                        },
                        cutout: '85%'
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>