<nav
    class="w-64 bg-[#0f172a] border-r border-[#1e293b] h-screen fixed top-0 left-0 z-30 transition-all duration-300 flex flex-col justify-between py-6">
    <div class="px-4 flex-grow overflow-y-auto custom-scrollbar">

        <!-- Logo Area (Optional visual separator if needed, otherwise just spacing) -->
        <div class="mb-8 px-2">
            <!-- Placeholder for logo alignment if needed, mainly providing top spacing -->
        </div>

        <div class="space-y-3">
            <!-- Dashboard -->
            <a href="{{ route('dashboard') }}"
                class="group flex items-center space-x-4 px-5 py-4 rounded-xl transition-all duration-200 {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'text-slate-400 hover:bg-[#1e293b] hover:text-white' }}">
                <svg class="w-6 h-6 transition-transform group-hover:scale-110 {{ request()->routeIs('dashboard') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                    </path>
                </svg>
                <span style="font-size: 16px; font-weight: 700; letter-spacing: 0.025em;">Dashboard</span>
            </a>

            <!-- Customers -->
            <a href="{{ route('customers.index') }}"
                class="group flex items-center space-x-4 px-5 py-4 rounded-xl transition-all duration-200 {{ request()->routeIs('customers.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'text-slate-400 hover:bg-[#1e293b] hover:text-white' }}">
                <svg class="w-6 h-6 transition-transform group-hover:scale-110 {{ request()->routeIs('customers.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                    </path>
                </svg>
                <span style="font-size: 16px; font-weight: 700; letter-spacing: 0.025em;">Customers</span>
            </a>

            <!-- Sales -->
            <a href="{{ route('sales.index') }}"
                class="group flex items-center space-x-4 px-5 py-4 rounded-xl transition-all duration-200 {{ request()->routeIs('sales.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'text-slate-400 hover:bg-[#1e293b] hover:text-white' }}">
                <svg class="w-6 h-6 transition-transform group-hover:scale-110 {{ request()->routeIs('sales.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                <span style="font-size: 16px; font-weight: 700; letter-spacing: 0.025em;">Sales</span>
            </a>

            <!-- Payments -->
            <a href="{{ route('payments.index') }}"
                class="group flex items-center space-x-4 px-5 py-4 rounded-xl transition-all duration-200 {{ request()->routeIs('payments.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'text-slate-400 hover:bg-[#1e293b] hover:text-white' }}">
                <svg class="w-6 h-6 transition-transform group-hover:scale-110 {{ request()->routeIs('payments.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                    </path>
                </svg>
                <span style="font-size: 16px; font-weight: 700; letter-spacing: 0.025em;">Payments</span>
            </a>

            <!-- Expenses -->
            <a href="{{ route('expenses.index') }}"
                class="group flex items-center space-x-4 px-5 py-4 rounded-xl transition-all duration-200 {{ request()->routeIs('expenses.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'text-slate-400 hover:bg-[#1e293b] hover:text-white' }}">
                <svg class="w-6 h-6 transition-transform group-hover:scale-110 {{ request()->routeIs('expenses.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z">
                    </path>
                </svg>
                <span style="font-size: 16px; font-weight: 700; letter-spacing: 0.025em;">Expenses</span>
            </a>

            <!-- Expense Categories -->
            <a href="{{ route('expense-categories.index') }}"
                class="group flex items-center space-x-4 px-5 py-4 rounded-xl transition-all duration-200 {{ request()->routeIs('expense-categories.*') ? 'bg-blue-600 text-white shadow-lg shadow-blue-900/40' : 'text-slate-400 hover:bg-[#1e293b] hover:text-white' }}">
                <svg class="w-6 h-6 transition-transform group-hover:scale-110 {{ request()->routeIs('expense-categories.*') ? 'text-white' : 'text-slate-500 group-hover:text-white' }}"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
                <span style="font-size: 16px; font-weight: 700; letter-spacing: 0.025em;">Categories</span>
            </a>
        </div>
    </div>

    <!-- Footer/Bottom Padding Area -->
    <div class="px-6 py-4 border-t border-[#1e293b] text-center">
        <span class="text-[10px] font-bold text-slate-600 uppercase tracking-widest">v1.0.0</span>
    </div>
</nav>