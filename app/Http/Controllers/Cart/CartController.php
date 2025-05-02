<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index(Request $request)
    {
        // Retrieve the user's cart items
        $userId = $request->user()->id ?? null;
        $cart = json_decode(request()->cookie("cart_{$userId}"), true) ?? [];

        // Debugging: Log the cart data
        Log::info('Cart Data:', ['cart' => $cart]);

        return view('cart.index', compact('cart'));
    }
}
