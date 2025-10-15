<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        return view('admin.dashboard', compact('user', 'totalUsers', 'totalProducts', 'totalOrders'));	
    }
}
