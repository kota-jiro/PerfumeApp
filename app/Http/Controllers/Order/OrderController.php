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
        $users = User::where('usertype', 'user')->get();

        $userFilter = Auth::check() && Auth::user()->usertype === 'user'
            ? Auth::id()
            : $request->input('user_id');

        $statusFilter = $request->input('status');

        // Build base query
        $ordersQuery = Order::with('user', 'product');

        if ($userFilter) {
            $ordersQuery->where('user_id', $userFilter);
        }

        if ($statusFilter) {
            $ordersQuery->where('status', $statusFilter);
        }

        // Clone query for totals (before pagination)
        $totalsQuery = clone $ordersQuery;

        // Get paginated orders
        $orders = $ordersQuery->orderBy('id', 'desc')->paginate(5)->appends([
            'user_id' => $request->user_id,
            'status' => $request->status,
        ]);

        // Totals (using unpaginated filtered query)
        $totalOrders = $totalsQuery->count();
        $totalPending = (clone $totalsQuery)->where('status', 'Pending')->count();
        $totalProcessing = (clone $totalsQuery)->where('status', 'Processing')->count();
        $totalOutForDelivery = (clone $totalsQuery)->where('status', 'Out for Delivery')->count();
        $totalCompleted = (clone $totalsQuery)->where('status', 'Completed')->count();
        $totalCancelled = (clone $totalsQuery)->where('status', 'Cancelled')->count();

        return view('admin.order.index', compact(
            'users',
            'orders',
            'totalOrders',
            'totalPending',
            'totalProcessing',
            'totalOutForDelivery',
            'totalCompleted',
            'totalCancelled',
            'userFilter',
            'statusFilter'
        ));
    }




    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        $order->update([
            'status' => $request->input('status'),
        ]);

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }
    // Checkout single product from cart
    public function checkout($id)
    {
        $user = Auth::user();

        if (empty($user->phone) || empty($user->address)) {
            return redirect()->back()->with('error', 'Please update your phone and address before checking out.');
        }

        $cartItem = Cart::where('id', $id)->where('user_id', Auth::id())->first();

        if (!$cartItem) {
            return redirect()->back()->with('error', 'Cart item not found.');
        }

        $additionalPrice = 0; // Default additional price

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
            'payment_method' => 'COD', // Assuming cash payment for simplicity
            'address' => $user->address,
            'phone' => $user->phone,
        ]);

        $cartItem->delete();

        return redirect()->route('client.orders.index')->with('success', 'Product checked out successfully!');
    }

    // Checkout all cart items
    public function checkoutAll()
    {
        $user = Auth::user();

        if (empty($user->phone) || empty($user->address)) {
            return redirect()->back()->with('error', 'Please update your phone and address before checking out.');
        }

        $cartItems = Cart::where('user_id', Auth::id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'No items in the cart.');
        }

        $additionalPrice = 0; // Default additional price
        foreach ($cartItems as $cartItem) {
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
                'payment_method' => 'COD', // Assuming cash payment for simplicity
                'address' => $user->address,
                'phone' => $user->phone,
            ]);
        }

        Cart::where('user_id', Auth::id())->delete();

        return redirect()->route('client.orders.index')->with('success', 'All products checked out successfully!');
    }
}
