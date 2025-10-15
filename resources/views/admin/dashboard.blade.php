<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[var(--primary-color)] leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6 bg-[var(--background-color)] text-[var(--text-color)]">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg p-8">
                <h3 class="text-3xl font-bold text-[var(--primary-color)] mb-4">{{ __("Welcome back, {$user->firstname}") }}</h3>
                <p class="mt-4 text-lg text-gray-600 leading-relaxed">Manage your users and products efficiently using the options below.</p>

                <!-- Stats Section -->
                <!-- Stats Section -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6 stats-section">
                    <!-- Total Users -->
                    <div class="bg-blue-100 border border-blue-300 rounded-xl p-6 text-center shadow hover:shadow-lg transition-all stat-card">
                        <h2 class="text-4xl font-bold text-blue-700 mt-4">{{ $totalUsers }}</h2>
                        <p class="text-lg text-blue-600">Total Users</p>
                    </div>

                    <!-- Total Products -->
                    <div class="bg-yellow-100 border border-yellow-300 rounded-xl p-6 text-center shadow hover:shadow-lg transition-all stat-card">
                        <h2 class="text-4xl font-bold text-yellow-700 mt-4">{{ $totalProducts }}</h2>
                        <p class="text-lg text-yellow-600">Total Products</p>
                    </div>

                    <!-- Total Orders -->
                    <div class="bg-purple-100 border border-purple-300 rounded-xl p-6 text-center shadow hover:shadow-lg transition-all stat-card">
                        <h2 class="text-4xl font-bold text-purple-700 mt-4">{{ $totalOrders }}</h2>
                        <p class="text-lg text-purple-600">Total Orders</p>
                    </div>
                </div>




                <!-- Management Links -->
                <div class="links mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 justify-center">
                    <a href="users" class="management-link text-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 11a4 4 0 100-8 4 4 0 000 8z" />
                            <path fill-rule="evenodd" d="M.458 16.043A10.014 10.014 0 0110 14c3.02 0 5.77 1.28 7.542 3.293A1 1 0 0116 19H4a1 1 0 01-.958-1.293z" clip-rule="evenodd" />
                        </svg>
                        Manage Users
                    </a>

                    <a href="products" class="management-link text-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 2a1 1 0 011 1v1h2.5A2.5 2.5 0 0116 6.5V8h1a1 1 0 011 1v7a1 1 0 01-1 1h-2v1a1 1 0 11-2 0v-1H7v1a1 1 0 11-2 0v-1H3a1 1 0 01-1-1V9a1 1 0 011-1h1V6.5A2.5 2.5 0 017.5 4H10V3a1 1 0 011-1zm-3 5h6V6.5A.5.5 0 0012.5 6h-5a.5.5 0 00-.5.5V7z" />
                        </svg>
                        Manage Products
                    </a>
                    <a href="orders" class="management-link text-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart-fill" viewBox="0 0 16 16">
                            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2" />
                        </svg>
                        Manage Orders
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
    :root {
        --primary-color: #8e1c1c;
        --secondary-color: #6b0f0f;
        --text-color: #fff;
        --background-color: #f8e0e0;
    }

    h3 {
        color: var(--primary-color);
        font-size: 2rem;
        font-weight: 600;
        margin-bottom: 1rem;
        text-align: center;
        max-width: 600px;
        margin: 0 auto;
        padding: 1rem;
        background-color: rgba(142, 28, 28, 0.1);
        border-radius: 10px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
    }

    p {
        color: text-gray-600;
        margin-bottom: 1rem;
        line-height: 1.5;
        text-align: center;
        max-width: 600px;
        margin: 0 auto;
        padding: 1rem;
    }

    .stats-section {
        margin-bottom: 3rem;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        justify-items: center;
        align-items: center;
        gap: 1.5rem;
        padding: 1rem;
    }

    .stat-card {
        background-color: rgba(0, 255, 133, 0.1);
        border: 1px solid rgba(0, 255, 133, 0.2);
        border-radius: 10px;
        padding: 1rem;
        text-align: center;
        transition: transform 0.3s ease;
        min-width: 350px;
        max-width: 550px;
        height: 200px;
    }

    .stat-card:hover {
        transform: translateY(-5px);
    }

    .stat-card h2 {
        font-size: 2.5rem;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .stat-card p {
        color: #888;
        font-size: 1.25rem;
    }

    .management-link {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background-color: var(--primary-color);
        color: white;
        font-weight: 600;
        padding: 1.5rem 2rem;
        border-radius: 10px;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease;
        width: 20%;
        text-align: center;
        margin: 0 auto;
    }

    .management-link svg {
        width: 50px;
        height: 50px;
        margin-bottom: 10px;
    }

    .links a {
        margin-bottom: 2rem;
    }

    .links {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        padding: 1rem;
    }

    .management-link:hover {
        background-color: var(--secondary-color);
    }

    @media (max-width: 768px) {
        .management-link {
            width: 80%;
            padding: 1rem;
            text-align: center;
        }
    }
</style>
``
