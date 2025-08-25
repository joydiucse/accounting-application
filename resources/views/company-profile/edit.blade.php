<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Edit Company Profile') }}
            </h2>
            <a href="{{ route('company-profile.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Back to Profile
            </a>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            <form method="POST" action="{{ route('company-profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Current Logo -->
                    @if($companyProfile->logo)
                        <div class="md:col-span-2 text-center mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Current Logo</label>
                            <img src="{{ asset('storage/' . $companyProfile->logo) }}" alt="Current Logo" class="mx-auto h-24 w-auto object-contain">
                        </div>
                    @endif

                    <!-- Company Name -->
                    <div>
                        <x-input-label for="name" :value="__('Company Name')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $companyProfile->name)" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Phone -->
                    <div>
                        <x-input-label for="phone" :value="__('Phone')" />
                        <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone', $companyProfile->phone)" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $companyProfile->email)" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Website -->
                    <div>
                        <x-input-label for="website" :value="__('Website')" />
                        <x-text-input id="website" class="block mt-1 w-full" type="url" name="website" :value="old('website', $companyProfile->website)" placeholder="https://example.com" />
                        <x-input-error :messages="$errors->get('website')" class="mt-2" />
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <x-input-label for="address" :value="__('Address')" />
                        <textarea id="address" name="address" rows="3" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('address', $companyProfile->address) }}</textarea>
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    <!-- Logo -->
                    <div class="md:col-span-2">
                        <x-input-label for="logo" :value="__('Company Logo')" />
                        <input id="logo" type="file" name="logo" accept="image/*" class="block mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100" />
                        <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF up to 2MB. Leave empty to keep current logo.</p>
                        <x-input-error :messages="$errors->get('logo')" class="mt-2" />
                    </div>
                </div>

                <div class="flex items-center justify-end mt-6">
                    <a href="{{ route('company-profile.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150 mr-3">
                        Cancel
                    </a>
                    <x-primary-button class="ml-3">
                        {{ __('Update Profile') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>