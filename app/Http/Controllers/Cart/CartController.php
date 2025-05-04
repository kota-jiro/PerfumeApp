<?php

namespace App\Http\Controllers\Cart;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\View;

class CartController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $carts = Cart::with('product')->where('user_id', Auth::id())->get();
        $cartCount = $carts->count();
        return view('cart.index', compact('carts', 'cartCount'));
    }
    /* 
    public function create()
    {
        $products = Cart::all();
        return view('cart.create', compact('products'));
    } */

    public function store(Request $request)
    {
        if (!Auth::check()) {
            abort(403, 'You must be logged in to add to cart.');
        }

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'size' => 'required|in:small,medium,large',
            'quantity' => 'nullable|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        $price = $product->{'price_' . $request->size};
        $quantity = $request->input('quantity', 1);

        $existing = Cart::where([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'size' => $request->size
        ])->first();

        if ($existing) {
            $existing->quantity += $quantity;
            $existing->save();
        } else {
            if ($request->size === 'small') {
            $price += 300;
            } elseif ($request->size === 'medium') {
            $price += 500;
            } elseif ($request->size === 'large') {
            $price += 800;
            }

            Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'size' => $request->size,
            'price' => $price,
            'quantity' => $quantity
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Product added to cart.']);
    }



    public function edit(Cart $cart)
    {
        $this->authorize('update', $cart);
        $products = Product::all();
        return view('cart.edit', compact('cart', 'products'));
    }

    public function update(Request $request, Cart $cart)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart->quantity = $request->quantity;
        $cart->save();

        return redirect()->route('cart.index')->with('success', 'Cart updated.');
    }

    public function destroy($id)
    {
        $cart = Cart::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $cart->delete();

        return redirect()->route('cart.index')->with('success', 'Cart item "' . $cart->product->title . '" removed.');
    }
}
