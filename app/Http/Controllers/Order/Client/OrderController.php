<?php

namespace App\Http\Controllers\Order\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('product')
            ->where('user_id', Auth::id()) // Fetch orders for the logged-in user
            ->paginate(10); // Paginate the results

        return view('client.orders.index', compact('orders'));
    }

    public function show($id)
    {
        // Get the order by ID
        $order = Order::with('product')->findOrFail($id);

        return view('client.order.show', compact('order'));
    }
}
