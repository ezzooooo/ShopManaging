<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class ProductController extends Controller
{
    public function index() {
        $products = Product::where('store_id', session('store_id'))->get();

        return view('product.index', ['products' => $products]);
    }

    public function create() {
        return view('product.create');
    }

    public function store(Request $request) {
        $product = new Product;

        $product->store_id = session('store_id');
        $product->name = $request->name;
        $product->eng_name = $request->eng_name;
        $product->price = $request->price;
        $product->exp = $request->exp;

        $product->save();

        return redirect()->route('product.index');
    }
}
