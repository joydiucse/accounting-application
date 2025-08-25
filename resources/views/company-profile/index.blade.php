<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Company Profile') }}
            </h2>
            @if($companyProfile)
                <a href="{{ route('company-profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Edit Profile
                </a>
            @else
                <a href="{{ route('company-profile.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Create Profile
                </a>
            @endif
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm rounded-lg">
        <div class="p-6">
            @if($companyProfile)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Company Logo -->
                    <div class="md:col-span-2 text-center">
                        @if($companyProfile->logo)
                            <img src="{{ asset('storage/' . $companyProfile->logo) }}" alt="Company Logo" class="mx-auto h-32 w-auto object-contain">
                        @else
                            <div class="mx-auto h-32 w-32 bg-gray-200 rounded-lg flex items-center justify-center">
                                <span class="text-gray-500 text-sm">No Logo</span>
                            </div>
                        @endif
                    </div>

                    <!-- Company Information -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Company Name</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $companyProfile->name ?? 'Not set' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Address</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $companyProfile->address ?? 'Not set' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $companyProfile->phone ?? 'Not set' }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $companyProfile->email ?? 'Not set' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Website</label>
                            <p class="mt-1 text-sm text-gray-900">
                                @if($companyProfile->website)
                                    <a href="{{ $companyProfile->website }}" target="_blank" class="text-blue-600 hover:text-blue-800">{{ $companyProfile->website }}</a>
                                @else
                                    Not set
                                @endif
                            </p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $companyProfile->updated_at->format('M d, Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No company profile</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating a company profile.</p>
                    <div class="mt-6">
                        <a href="{{ route('company-profile.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Create Company Profile
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>