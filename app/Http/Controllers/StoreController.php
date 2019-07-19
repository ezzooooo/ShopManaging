<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Notice;
use App\Product;

class StoreController extends Controller
{
    public function search(Request $request) 
    {
        $name = $request->get('store_name');
        $notices = Notice::all();
        $products = Product::all();

        $items = User::where('name', 'LIKE', "%$name%")->get();

        return view('store_search', ['items' => $items, 'notices' => $notices, 'products' => $products]);
    }
}
