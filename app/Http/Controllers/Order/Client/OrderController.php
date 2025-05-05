<?php

namespace App\Http\Controllers\Order\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('product')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'processing', 'out for delivery']); // Filter by specific statuses

        // Filter by order date if provided
        if ($request->has('date')) {
            $query->whereDate('updated_at', $request->input('date'));
        }

        $orderCount = $query->count(); // Get the total number of filtered orders
        $orders = $query->paginate(perPage: 4); // Paginate the results

        return view('client.orders.index', compact('orders', 'orderCount'));
    }
    
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $order->update([
            'status' => $request->input('status'),
        ]);

        $message = $request->input('status') === 'completed' 
            ? 'Order received successfully!' 
            : 'Order canceled successfully!';

        return redirect()->back()->with('success', $message);
    }

    public function show($id)
    {
        // Get the order by ID
        $order = Order::with('product')->findOrFail($id);

        return view('client.order.show', compact('order'));
    }
    
    public function updatePaymentMethod(Request $request, Order $order)
    {
        $request->validate([
            'payment_method' => 'required|string',
        ]);

        $order->update([
            'payment_method' => $request->input('payment_method'),
        ]);

        return redirect()->back()->with('success', 'Payment method updated successfully!');
    }
}
