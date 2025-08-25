<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-bold text-2xl text-slate-800">
                    Dashboard
                </h2>
                <p class="text-slate-600 mt-1">Welcome back, {{ auth()->user()->name }}!</p>
            </div>
            @if(auth()->user()->canManage())
                <button class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-2.5 rounded-xl font-semibold hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 shadow-lg hover:shadow-xl flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span>Create Transaction</span>
                </button>
            @endif
        </div>
    </x-slot>

    <div class="space-y-8">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Outstanding Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 mb-1">Total Outstanding</p>
                        <p class="text-3xl font-bold text-slate-900">৳{{ number_format($totalIncome, 2) }}</p>
                        <div class="flex items-center mt-2">
                            <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">+25%</span>
                            <span class="text-xs text-slate-500 ml-2">vs last month</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Total Expenses Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 mb-1">Total Expenses</p>
                        <p class="text-3xl font-bold text-slate-900">৳{{ number_format($totalExpenses, 2) }}</p>
                        <div class="flex items-center mt-2">
                            <span class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full font-medium">+12%</span>
                            <span class="text-xs text-slate-500 ml-2">vs last month</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Net Profit Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 mb-1">Net Profit</p>
                        <p class="text-3xl font-bold {{ $currentBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                            ৳{{ number_format($currentBalance, 2) }}
                        </p>
                        <div class="flex items-center mt-2">
                            <span class="text-xs {{ $currentBalance >= 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} px-2 py-1 rounded-full font-medium">
                                {{ $currentBalance >= 0 ? '+' : '-' }}8%
                            </span>
                            <span class="text-xs text-slate-500 ml-2">vs last month</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-r {{ $currentBalance >= 0 ? 'from-blue-500 to-indigo-500' : 'from-orange-500 to-red-500' }} rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- This Month Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow duration-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-slate-600 mb-1">This Month</p>
                        <p class="text-3xl font-bold text-slate-900">৳{{ number_format(abs($monthlyProfit ?? 0), 2) }}</p>
                        <div class="flex items-center mt-2">
                            <span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded-full font-medium">+15%</span>
                            <span class="text-xs text-slate-500 ml-2">vs last month</span>
                        </div>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-indigo-500 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Summary Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Revenue Chart -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Revenue Overview</h3>
                        <p class="text-sm text-slate-600">Monthly income vs expenses</p>
                    </div>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 text-xs font-medium bg-slate-100 text-slate-700 rounded-lg">1M</button>
                        <button class="px-3 py-1 text-xs font-medium bg-slate-100 text-slate-700 rounded-lg">3M</button>
                        <button class="px-3 py-1 text-xs font-medium bg-indigo-100 text-indigo-700 rounded-lg">6M</button>
                        <button class="px-3 py-1 text-xs font-medium bg-slate-100 text-slate-700 rounded-lg">1Y</button>
                    </div>
                </div>
                <canvas id="monthlyChart" height="300"></canvas>
            </div>

            <!-- Payroll Summary -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-slate-900">Payroll Summary</h3>
                    <a href="{{ route('reports.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">View report</a>
                </div>

                <div class="space-y-6">
                    <div class="text-center">
                        <p class="text-sm text-slate-600 mb-1">From 1-31 March 2024</p>
                    </div>

                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <p class="text-xs text-slate-600 mb-1">Payment</p>
                            <p class="text-lg font-bold text-slate-900">৳{{ number_format($totalIncome * 0.6, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 mb-1">Pending</p>
                            <p class="text-lg font-bold text-slate-900">৳{{ number_format($totalIncome * 0.25, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-600 mb-1">Paid</p>
                            <p class="text-lg font-bold text-slate-900">৳{{ number_format($totalIncome * 0.15, 2) }}</p>
                        </div>
                    </div>

                    <!-- Donut Chart -->
                    <div class="flex justify-center">
                        <canvas id="categoryChart" width="200" height="200"></canvas>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                            <p class="text-sm font-medium text-slate-700">Previous Payroll</p>
                            <p class="text-sm text-slate-600">March 1, 2024</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-slate-900">৳{{ number_format($totalExpenses * 0.8, 2) }}</p>
                            <div class="flex items-center justify-center mt-1">
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-medium">✓ PAID</span>
                            </div>
                        </div>

                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                            <p class="text-sm font-medium text-slate-700">Upcoming Payroll</p>
                            <p class="text-sm text-slate-600">March 6, 2024</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-slate-900">৳{{ number_format($totalExpenses * 0.6, 2) }}</p>
                            <div class="flex items-center justify-center mt-1">
                                <span class="text-xs bg-orange-100 text-orange-700 px-2 py-1 rounded-full font-medium">⏳ PENDING</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction History -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200">
            <div class="p-6 border-b border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Transaction History</h3>
                        <p class="text-sm text-slate-600">Recent financial activities</p>
                    </div>
                    <a href="{{ route('reports.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">See All</a>
                </div>
            </div>

            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recentIncomes->take(3) as $income)
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors duration-200">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $income->source }}</p>
                                    <p class="text-sm text-slate-600">{{ $income->category->name ?? 'Income' }}</p>
                                    <p class="text-xs text-slate-500">{{ $income->date->format('M d, Y') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-green-600">৳{{ number_format($income->amount, 2) }}</p>
                                <button class="text-xs bg-slate-800 text-white px-3 py-1 rounded-lg mt-1 hover:bg-slate-700 transition-colors duration-200">
                                    Send Invoice
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <p class="text-slate-500">No recent transactions found.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Chart.js Scripts -->
    <script>
        // Monthly Income vs Expenses Chart with enhanced styling
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        /*new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($monthlyLabels ?? ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']) !!},
                datasets: [{
                    label: 'Revenue',
                    data: {!! json_encode($monthlyIncomeData ?? [5251, 4800, 5500, 6200, 5800, 6500]) !!},
                    borderColor: '#6366f1',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#6366f1',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#6366f1',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        border: {
                            display: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9',
                            drawBorder: false
                        },
                        border: {
                            display: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 12
                            },
                            callback: function(value) {
                                return '$' + value.toLocaleString();
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });

        // Enhanced Category Breakdown Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Payment', 'Pending', 'Paid'],
                datasets: [{
                    data: [60, 25, 15],
                    backgroundColor: ['#8b5cf6', '#f97316', '#1f2937'],
                    borderWidth: 0,
                    cutout: '70%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        cornerRadius: 8,
                        displayColors: true
                    }
                }
            }
        });*/
    </script>
</x-app-layout>
