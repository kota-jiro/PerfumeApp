<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    <h3 class="text-xl font-bold text-[#8e1c1c]">{{ __("Welcome back, {$user->firstname}")}}</h3>
                    <p class="mt-4 text-gray-600">Manage your users and products efficiently using the options below.</p>

                    <!-- Card Section -->
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Total Users Card -->
                        <div class="bg-gray-100 p-6 rounded-lg shadow-md">
                            <h4 class="text-3xl font-bold text-[#8e1c1c]">{{ $totalUsers }}</h4>
                            <p class="text-gray-600">Total Users</p>
                        </div>

                        <!-- Total Products Card -->
                        <div class="bg-gray-100 p-6 rounded-lg shadow-md">
                            <h4 class="text-3xl font-bold text-[#8e1c1c]">{{ $totalProducts }}</h4>
                            <p class="text-gray-600">Total Products</p>
                        </div>
                    </div>

                    <!-- Management Links -->
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="users" class="flex items-center justify-center bg-[#8e1c1c] text-black font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-[#6b0f0f] transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 11a4 4 0 100-8 4 4 0 000 8z" />
                                <path fill-rule="evenodd" d="M.458 16.043A10.014 10.014 0 0110 14c3.02 0 5.77 1.28 7.542 3.293A1 1 0 0116 19H4a1 1 0 01-.958-1.293z" clip-rule="evenodd" />
                            </svg>
                            Manage Users
                        </a>

                        <a href="products" class="flex items-center justify-center bg-[#6b0f0f] text-black font-semibold py-3 px-6 rounded-lg shadow-md hover:bg-[#8e1c1c] transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 2a1 1 0 011 1v1h2.5A2.5 2.5 0 0116 6.5V8h1a1 1 0 011 1v7a1 1 0 01-1 1h-2v1a1 1 0 11-2 0v-1H7v1a1 1 0 11-2 0v-1H3a1 1 0 01-1-1V9a1 1 0 011-1h1V6.5A2.5 2.5 0 017.5 4H10V3a1 1 0 011-1zm-3 5h6V6.5A.5.5 0 0012.5 6h-5a.5.5 0 00-.5.5V7z" />
                            </svg>
                            Manage Products
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>