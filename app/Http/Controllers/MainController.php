<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\ProductsFilterRequest;

class MainController extends Controller
{
    public function index(ProductsFilterRequest $requset) {
        $productQuery = Product::query();

        if ($requset->filled('price_from')) {
            $productQuery->where('price', '>=', $requset->price_from);
        }

        if ($requset->filled('price_to')) {
            $productQuery->where('price', '<=', $requset->price_to);
        }

        $checkboxes = ['hit', 'new', 'recommend'];

        if ($requset->hasAny($checkboxes)) {
            $productQuery->where(function($query ) use ($requset, $checkboxes) {
                foreach($checkboxes as $field) {
                    $requset->has($field) && $query->orWhere($field, 1);
                }
            });
        }

        $products = $productQuery->paginate(6);

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
