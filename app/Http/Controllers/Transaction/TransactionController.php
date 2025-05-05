<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('product')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['completed', 'cancelled']); // Only include completed and cancelled statuses

        // Filter by order date if provided
        if ($request->has('date')) {
            $query->whereDate('updated_at', $request->input('date'));
        }

        $totalOrders = $query->count(); // Get the total number of orders with the specified statuses
        // Paginate and append query parameters (date filter)
        $orders = $query->paginate(4)->appends([
            'date' => $request->input('date'),
        ]);

        return view('client.history.transaction-history', compact('orders', 'totalOrders'));
    }
}
