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

    public function singleCategory($categoryCode) {
        $category = Category::where('code', $categoryCode)->first();
        return view('single-category', compact('category'));
    }

    public function singleProduct($categoryCode, $productCode = null) {

        $product = Product::where('code', $productCode)->first();
        $category = $product->category;

        return view('single-product', compact('category', 'product'));
    }
}
