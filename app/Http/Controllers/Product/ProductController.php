<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function indexAll()
    {
        $filteredProducts = Product::all(); // Keep it as a collection

        $maleCount = Product::where('category', 'Male Perfume')->count();
        $femaleCount = Product::where('category', 'Female Perfume')->count();
        $total = Product::count();

        return view('products.index', compact('filteredProducts', 'maleCount', 'femaleCount', 'total'));
    }

    public function filterByCategory(Request $request)
    {
        $category = $request->input('category');

        $filteredProducts = Product::when($category && $category !== 'All', function ($query) use ($category) {
            return $query->where('category', $category);
        })
            ->orderBy('id', 'asc')
            ->get();

        $maleCount = Product::where('category', 'Male Perfume')->count();
        $femaleCount = Product::where('category', 'Female Perfume')->count();
        $total = Product::count();

        return view('products.index', compact('filteredProducts', 'maleCount', 'femaleCount', 'total', 'category'));
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $filteredProducts = Product::where('title', 'LIKE', '%' . $search . '%')
            ->orWhere('category', 'LIKE', '%' . $search . '%')
            ->orWhere('description', 'LIKE', '%' . $search . '%')
            ->orderBy('id', 'asc')
            ->get();

        $maleCount = Product::where('category', 'Male Perfume')->count();
        $femaleCount = Product::where('category', 'Female Perfume')->count();
        $total = Product::count();

        return view('products.index', compact('filteredProducts', 'maleCount', 'femaleCount', 'total', 'search'));
    }
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }
}
