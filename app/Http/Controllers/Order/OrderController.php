<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('usertype', 'user')->get(); // Get all users with usertype 'user' for the filter
        $userFilter = Auth::check() && Auth::user()->usertype === 'user' ? Auth::id() : $request->input('user_id'); // Automatically filter by logged-in user if usertype is 'user'
        $statusFilter = $request->has('status') ? $request->input('status') : null; // Get the selected status from the request, default to null if not provided

        // Build the query for filtering orders
        $ordersQuery = Order::with('user', 'product');

        if ($userFilter) {
            $ordersQuery->where('user_id', $userFilter); // Filter orders by the selected user
        }

        if ($statusFilter) {
            $ordersQuery->where('status', $statusFilter); // Filter orders by the selected status
        }

        $orders = $ordersQuery->paginate(perPage: 2); // Paginate the filtered orders

        // Calculate totals dynamically based on the selected filter
        if ($statusFilter) {
            $totalPending = $statusFilter === 'Pending' ? $ordersQuery->count() : 0;
            $totalProcessing = $statusFilter === 'Processing' ? $ordersQuery->count() : 0;
            $totalCompleted = $statusFilter === 'Completed' ? $ordersQuery->count() : 0;
            $totalCancelled = $statusFilter === 'Cancelled' ? $ordersQuery->count() : 0;
            $totalOrders = $ordersQuery->count(); // Total number of filtered orders
        } else {
            $totalPending = Order::where('status', 'Pending')->count();
            $totalProcessing = Order::where('status', 'Processing')->count();
            $totalCompleted = Order::where('status', 'Completed')->count();
            $totalCancelled = Order::where('status', 'Cancelled')->count();
            $totalOrders = Order::count(); // Total number of all orders
        }

        return view('admin.order.index', compact(
            'users',
            'orders',
            'totalOrders',
            'totalPending',
            'totalProcessing',
            'totalCompleted',
            'totalCancelled',
            'userFilter',
            'statusFilter'
        ));
    }
    // Checkout single product from cart
    public function checkout($id)
    {
        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$cartItem) {
            return redirect()->back()->with('error', 'Cart item not found.');
        }

        $additionalPrice = 0;

        if ($cartItem->size === 'small') {
            $additionalPrice = 300;
        } elseif ($cartItem->size === 'medium') {
            $additionalPrice = 500;
        } elseif ($cartItem->size === 'large') {
            $additionalPrice = 800;
        }

        $finalPrice = $cartItem->price + $additionalPrice;

        Order::create([
            'user_id'    => Auth::id(),
            'product_id' => $cartItem->product_id,
            'size'       => $cartItem->size,
            'quantity'   => $cartItem->quantity,
            'price'      => $cartItem->price,
            'status'     => 'Pending',
            'total_price' => $finalPrice * $cartItem->quantity, // Calculate total price
        ]);

        $cartItem->delete();

        return redirect()->back()->with('success', 'Product checked out successfully!');
    }

    // Checkout all cart items
    public function checkoutAll()
    {
        $cartItems = Cart::where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'No items in the cart.');
        }

        foreach ($cartItems as $cart) {
            Order::create([
                'user_id'    => Auth::id(),
                'product_id' => $cart->product_id,
                'size'       => $cart->size,
                'quantity'   => $cart->quantity,
                'price'      => $cart->price,
                'status'     => 'Pending',
            ]);

            $cart->delete();
        }

        return redirect()->back()->with('success', 'All products checked out successfully!');
    }
}
