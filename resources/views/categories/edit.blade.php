<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Category: ') . $category->name }}
            </h2>
            <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Back to Categories
            </a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <form method="POST" action="{{ route('categories.update', $category) }}">
                @csrf
                @method('PUT')

                <!-- Name -->
                <div class="mb-4">
                    <x-input-label for="name" :value="__('Category Name')" />
                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $category->name)" required autofocus autocomplete="name" />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Type -->
                <div class="mb-4">
                    <x-input-label for="type" :value="__('Category Type')" />
                    <select id="type" name="type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        <option value="">Select Category Type</option>
                        <option value="income" {{ old('type', $category->type) == 'income' ? 'selected' : '' }}>Income</option>
                        <option value="expense" {{ old('type', $category->type) == 'expense' ? 'selected' : '' }}>Expense</option>
                    </select>
                    <x-input-error :messages="$errors->get('type')" class="mt-2" />
                </div>

                <!-- Is Default -->
                <div class="mb-4">
                    <div class="flex items-center">
                        <input id="is_default" type="checkbox" name="is_default" value="1" {{ old('is_default', $category->is_default) ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ $category->is_default ? 'disabled' : '' }}>
                        <label for="is_default" class="ml-2 text-sm text-gray-600">{{ __('Set as default category') }}</label>
                        @if($category->is_default)
                            <span class="ml-2 text-xs text-gray-500">(Default categories cannot be changed)</span>
                        @endif
                    </div>
                    <x-input-error :messages="$errors->get('is_default')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-3">
                        Cancel
                    </a>
                    <x-primary-button class="ml-3">
                        {{ __('Update Category') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>