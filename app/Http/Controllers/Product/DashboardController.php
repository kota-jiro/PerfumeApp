<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function indexAll()
    {
        $filteredProducts = Product::all();
        $maleCount = Product::where('category', 'Male Perfume')->count();
        $femaleCount = Product::where('category', 'Female Perfume')->count();
        $total = Product::count();
        return view('client.dashboard', compact('filteredProducts', 'maleCount', 'femaleCount', 'total'));
    }
    public function search(Request $request)
    {
        $search = $request->input('search');
        $filteredProducts = Product::where('title', 'LIKE', '%' . $search . '%')
            ->orWhere('category', 'LIKE', '%' . $search . '%')
            ->orWhere('description', 'LIKE', '%' . $search . '%')
            ->orderBy('id', 'asc')
            ->paginate(perPage: 10);


        $user = Auth::user();
        $maleCount = Product::where('category', 'Male Perfume')->count();
        $femaleCount = Product::where('category', 'Female Perfume')->count();
        $total = Product::count();
        
        return view('client.dashboard', compact('filteredProducts', 'user', 'maleCount', 'femaleCount', 'total', 'search'));
    }
}
