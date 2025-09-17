<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Income Management') }}
            </h2>
            @if(auth()->user()->canManage())
                <a href="{{ route('incomes.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Add New Income
                </a>
            @endif
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Search and Filter Section -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <form method="GET" action="{{ route('incomes.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search by source or description..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category_id" id="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700">From Date</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700">To Date</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                    </div>
                    <div class="md:col-span-4 flex space-x-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Filter
                        </button>
                        <a href="{{ route('incomes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Clear
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Income Table -->
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                @if(auth()->user()->canManage())
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($incomes as $income)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $income->date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $income->source }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $income->category->name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">
                                         ${{ number_format($income->amount, 2) }}
                                     </td>
                                     <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                         @if($income->from_dollar)
                                             <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                 Dollar Source
                                             </span>
                                         @else
                                             <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                 Regular
                                             </span>
                                         @endif
                                     </td>
                                     <td class="px-6 py-4 text-sm text-gray-500">
                                         {{ $income->description ?? '-' }}
                                     </td>
                                    @if(auth()->user()->canManage())
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('incomes.show', $income) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                                <a href="{{ route('incomes.edit', $income) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                                <form action="{{ route('incomes.destroy', $income) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this income record?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                 <tr>
                                     <td colspan="{{ auth()->user()->canManage() ? '7' : '6' }}" class="px-6 py-4 text-center text-sm text-gray-500">
                                         No income records found.
                                     </td>
                                 </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="mt-4">
                    {{ $incomes->appends(request()->query())->links() }}
                </div>
                
                <!-- Total Summary -->
                <div class="mt-4 bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm text-gray-600">
                        Total Income: <span class="font-semibold text-green-600">৳{{ number_format($totalIncome, 2) }}</span>
                        ({{ $incomes->total() }} records)
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>