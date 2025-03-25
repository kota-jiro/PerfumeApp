<style>
    :root {
        --primary-color: #8e1c1c;
        --secondary-color: #6b0f0f;
        --text-color: #fff;
        --background-color: #f8e0e0;
    }

    nav {
        background-color: var(--primary-color);
        color: var(--text-color);
        padding: 1rem 0;
    }

    a {
        color: var(--text-color);
        text-decoration: none;
        margin-right: 20px;
        font-weight: bold;
    }

    a:hover {
        color: var(--background-color);
    }

    .logo {
        font-size: 1.5rem;
        font-weight: bold;
        display: flex;
        align-items: center;
    }

    .logo img {
        height: 24px;
        /* Adjusted logo size */
        margin-right: 10px;
    }

    .nav-links {
        display: flex;
        align-items: center;
    }

    .nav-link.active {
        border-bottom: 2px solid var(--text-color);
    }

    .user-info {
        margin-left: auto;
        display: flex;
        align-items: center;
    }

    .user-info button {
        background-color: var(--secondary-color);
        border: none;
        color: var(--text-color);
        padding: 8px 16px;
        border-radius: 5px;
        margin-left: 10px;
        cursor: pointer;
    }

    .user-info button:hover {
        background-color: var(--primary-color);
    }

    .mobile-menu {
        display: none;
    }

    @media (max-width: 640px) {
        .nav-links {
            display: none;
        }

        .mobile-menu {
            display: block;
        }
    }
</style>

<nav x-data="{ open: false }">
    <div class="flex justify-between items-center px-4">
        <a href="{{ Auth::user()->usertype == 'admin' ? route('admin.dashboard') : route('dashboard') }}">
            <span class="logo">
                <img src="/images/logo.png" alt="Logo" />
                Leo's Perfume
            </span>
        </a>

        <!-- Desktop Navigation -->
        <div class="nav-links">
            @if(Auth::user()->usertype == 'admin')
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Admin Dashboard</a>
            <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">Users Management</a>
            <a href="{{ route('admin.products') }}" class="nav-link {{ request()->routeIs('admin.products') ? 'active' : '' }}">Admin Products</a>
            @else
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.index') ? 'active' : '' }}">Products</a>
            @endif
        </div>

        <!-- User Dropdown -->
        <div class="user-info">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="flex items-center">{{ Auth::user()->firstname }}
                        <svg class="ml-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('profile.edit')">Profile</x-dropdown-link>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">Log Out</x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>

        <!-- Mobile Menu Button -->
        <button @click="open = ! open" class="mobile-menu">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path :class="{'hidden': open, 'block': ! open}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{'block': open, 'hidden': ! open}" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</nav>