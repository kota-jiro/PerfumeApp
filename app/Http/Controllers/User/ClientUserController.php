<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientUserController extends Controller
{
    public function index()
    {

        // Return the view with the user data
        return view('client.about.about');
    }
}
