<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;

class MainController extends Controller
{
    public function home() {
        $products = Product::all();
        return view('index', compact('products'));
    }

    public function categories() {
        $categories =  Category::all();
        return view('categories', compact('categories'));
    }

    public function singleCategory($slug) {
        $category = Category::where('code', $slug)->first();
        return view('single-category', compact('category'));
    }

    public function singleProduct($category, $product = null) {
        return view('single-product', compact('category', 'product'));
    }
}
