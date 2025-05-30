<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.navigation', function ($view) {
            $cartCount = Auth::check() ? Cart::where('user_id', Auth::id())->count() : 0;
            $view->with('cartCount', $cartCount);
        });

        View::composer('layouts.navigation', function ($view) {
            $orderCount = Auth::check() ? Order::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'processing', 'out for delivery'])
            ->count() : 0;
            $view->with('orderCount', $orderCount);
        });
    }
}
