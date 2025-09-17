<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Income') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('incomes.show', $income) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    View
                </a>
                <a href="{{ route('incomes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <form method="POST" action="{{ route('incomes.update', $income) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Date -->
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700">Date *</label>
                            <input type="date" name="date" id="date" value="{{ old('date', $income->date->format('Y-m-d')) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('date') border-red-500 @enderror">
                            @error('date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Amount -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount *</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">৳</span>
                                </div>
                                <input type="number" name="amount" id="amount" step="0.01" min="0" value="{{ old('amount', $income->amount) }}" required class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('amount') border-red-500 @enderror" placeholder="0.00">
                            </div>
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- From Dollar -->
                        <div class="flex items-center">
                            <input type="checkbox" name="from_dollar" id="from_dollar" value="1" {{ old('from_dollar', $income->from_dollar) ? 'checked' : '' }} class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded" onchange="toggleDollarFields()">
                            <label for="from_dollar" class="ml-2 block text-sm text-gray-700">
                                Income from Dollar Source
                            </label>
                        </div>

                        <!-- Dollar Fields (Hidden by default) -->
                        <div id="dollar-fields" class="md:col-span-2 {{ old('from_dollar', $income->from_dollar) ? '' : 'hidden' }}">
                            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                <h4 class="text-sm font-medium text-blue-800 mb-3">Dollar Transaction Details</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- USD Amount -->
                                    <div>
                                        <label for="usd_amount" class="block text-sm font-medium text-gray-700">USD Amount *</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">$</span>
                                            </div>
                                            <input type="number" name="usd_amount" id="usd_amount" step="0.01" min="0" value="{{ old('usd_amount', $income->usd_amount ?? '') }}" class="pl-7 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="0.00" oninput="calculateBDT()">
                                        </div>
                                        @error('usd_amount')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Exchange Rate -->
                                    <div>
                                        <label for="exchange_rate" class="block text-sm font-medium text-gray-700">Exchange Rate (USD to BDT) *</label>
                                        <input type="number" name="exchange_rate" id="exchange_rate" step="0.0001" min="0" value="{{ old('exchange_rate', $income->exchange_rate ?? '110.00') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="110.00" oninput="calculateBDT()">
                                        @error('exchange_rate')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- BDT Amount (Calculated) -->
                                    <div>
                                        <label for="bdt_amount_display" class="block text-sm font-medium text-gray-700">BDT Amount (Calculated)</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <span class="text-gray-500 sm:text-sm">৳</span>
                                            </div>
                                            <input type="text" id="bdt_amount_display" class="pl-7 block w-full rounded-md border-gray-300 bg-gray-50 shadow-sm" placeholder="0.00" readonly>
                                        </div>
                                        <input type="hidden" name="bdt_amount" id="bdt_amount" value="{{ old('bdt_amount', $income->bdt_amount ?? '') }}">
                                    </div>
                                </div>
                                <p class="text-xs text-blue-600 mt-2">Note: The BDT amount will be automatically calculated and used as the main amount for this income.</p>
                            </div>
                        </div>

                        <!-- Source -->
                        <div class="md:col-span-2">
                            <label for="source" class="block text-sm font-medium text-gray-700">Source *</label>
                            <input type="text" name="source" id="source" value="{{ old('source', $income->source) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('source') border-red-500 @enderror" placeholder="e.g., Salary, Freelance, Investment">
                            @error('source')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="md:col-span-2">
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Category *</label>
                            <select name="category_id" id="category_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('category_id') border-red-500 @enderror">
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $income->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 @error('description') border-red-500 @enderror" placeholder="Optional description...">{{ old('description', $income->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6 space-x-3">
                        <a href="{{ route('incomes.show', $income) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Update Income
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
function toggleDollarFields() {
    const checkbox = document.getElementById('from_dollar');
    const dollarFields = document.getElementById('dollar-fields');
    
    if (checkbox.checked) {
        dollarFields.classList.remove('hidden');
        calculateBDT();
    } else {
        dollarFields.classList.add('hidden');
        // Clear the main amount field when unchecked
        document.getElementById('amount').value = '';
    }
}

function calculateBDT() {
    const usdAmount = parseFloat(document.getElementById('usd_amount').value) || 0;
    const exchangeRate = parseFloat(document.getElementById('exchange_rate').value) || 0;
    const bdtAmount = usdAmount * exchangeRate;
    
    // Update display field
    document.getElementById('bdt_amount_display').value = bdtAmount.toFixed(2);
    // Update hidden field
    document.getElementById('bdt_amount').value = bdtAmount.toFixed(2);
    // Update main amount field
    document.getElementById('amount').value = bdtAmount.toFixed(2);
    
    // Show current balance for reference
    showCurrentBalance();
}

function showCurrentBalance() {
    fetch('/api/dollar-balance')
        .then(response => response.json())
        .then(data => {
            const balanceInfo = document.getElementById('balance-info');
            
            if (!balanceInfo) {
                const balanceDiv = document.createElement('div');
                balanceDiv.id = 'balance-info';
                balanceDiv.className = 'mt-2 text-sm text-blue-600';
                document.getElementById('usd_amount').parentNode.appendChild(balanceDiv);
            }
            
            const balanceElement = document.getElementById('balance-info');
            balanceElement.innerHTML = `<span class="text-blue-600">💰 Current dollar balance: $${data.formatted_balance}</span>`;
        })
        .catch(error => {
            console.error('Error fetching balance:', error);
        });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    const checkbox = document.getElementById('from_dollar');
    if (checkbox.checked) {
        calculateBDT();
    }
});
</script>

</x-app-layout>