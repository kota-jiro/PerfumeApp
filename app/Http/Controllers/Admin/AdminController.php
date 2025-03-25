<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
        return view('admin.dashboard', compact('user', 'totalUsers', 'totalProducts'));	
    }
}
