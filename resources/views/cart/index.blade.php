<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Your Cart') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (empty($cart))
                        <p>Your cart is empty.</p>
                    @else
                        {{-- Debugging: Display raw cart data --}}
                        <pre>{{ print_r($cart, true) }}</pre>

                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Size</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cart as $item)
                                    <tr>
                                        <td>{{ $item['product_name'] }}</td>
                                        <td>{{ ucfirst($item['size']) }}</td>
                                        <td>₱{{ number_format($item['price'], 2) }}</td>
                                        <td>{{ $item['quantity'] }}</td>
                                        <td>₱{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            <a href="{{ route('checkout.store') }}" class="btn btn-success">Check Out</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>