<?php

namespace App\Http\Controllers\Order;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->paginate(2);
        return view('admin.order.index', compact('orders'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed',
        ]);

        $order->update(['status' => $request->status]);

        return back()->with('success', 'Order status updated.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cart' => 'required|array',
            'cart.*.product_id' => 'required|exists:products,id',
            'cart.*.product_name' => 'required|string',
            'cart.*.price' => 'required|numeric',
            'cart.*.size' => 'required|string|in:small,medium,large',
            'cart.*.quantity' => 'required|integer|min:1',
        ]);

        DB::beginTransaction(); // Start a transaction

        try {
            $user = $request->user()->id ?? null; // Get the authenticated user
            $orderDetails = []; // To store order details for logging or further use

            foreach ($request->cart as $item) {
                $product = Product::find($item['product_id']);

                $stockField = 'stock_' . $item['size'];
                if ($product[$stockField] < $item['quantity']) {
                    throw new \Exception("Sorry, only {$product[$stockField]} of {$item['product_name']} ({$item['size']}) are available.");
                }

                // Deduct stock
                $product->decrement($stockField, $item['quantity']);

                // Create the order
                $order = Order::create([
                    'user_id' => $user->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'price' => $item['price'],
                    'size' => $item['size'],
                    'quantity' => $item['quantity'], // Save the quantity
                    'status' => 'pending',
                ]);

                // Add order details for logging or further use
                $orderDetails[] = [
                    'user_name' => $user->firstname . ' ' . $user->lastname,
                    'product_name' => $item['product_name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'size' => $item['size'],
                ];
            }

            DB::commit(); // Commit the transaction if everything is successful

            return response()->json([
                'message' => 'Order placed and stock updated.',
                'order_details' => $orderDetails, // Return order details for confirmation
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback the transaction on error

            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
