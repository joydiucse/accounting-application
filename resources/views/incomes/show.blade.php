<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Income Details') }}
            </h2>
            <div class="flex space-x-2">
                @if(auth()->user()->canManage())
                    <a href="{{ route('incomes.edit', $income) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Edit
                    </a>
                @endif
                <a href="{{ route('incomes.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Back to List
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-2xl mx-auto">
        <div class="bg-white overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $income->date->format('F d, Y') }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Amount</label>
                        <p class="mt-1 text-lg font-semibold text-green-600">${{ number_format($income->amount, 2) }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Source</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $income->source }}</p>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <p class="mt-1 text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $income->category->name ?? 'N/A' }}
                            </span>
                        </p>
                    </div>

                    @if($income->description)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $income->description }}</p>
                        </div>
                    @endif

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Created By</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $income->user->name }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Created At</label>
                        <p class="mt-1 text-sm text-gray-900">{{ $income->created_at->format('M d, Y g:i A') }}</p>
                    </div>

                    @if($income->updated_at != $income->created_at)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $income->updated_at->format('M d, Y g:i A') }}</p>
                        </div>
                    @endif
                </div>

                @if(auth()->user()->canManage())
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('incomes.edit', $income) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Edit Income
                            </a>
                            <form action="{{ route('incomes.destroy', $income) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this income record? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Delete Income
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>