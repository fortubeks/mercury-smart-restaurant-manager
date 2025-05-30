<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function dashboard()
    {
        return theme_view('store.dashboard');
    }
}
