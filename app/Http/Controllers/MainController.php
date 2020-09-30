<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Currency;
use App\Models\Subscription;
use App\Http\Requests\ProductsFilterRequest;
use App\Http\Requests\SubscriptionRequest;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;

class MainController extends Controller
{
    public function index(ProductsFilterRequest $requset) {
        $productQuery = Product::with('category');

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
                    $requset->has($field) && $query->$field();
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
        $category = Category::where('code', $categoryCode)->firstOrFail();
        return view('single-category', compact('category'));
    }

    public function singleProduct($categoryCode, $productCode = null) {
        $product = Product::byCode($productCode)->first();

        if (Auth::check() && Auth::user()->isAdmin()) {
                $product = Product::withTrashed()->byCode($productCode)->firstOrFail();
        }else {
            $product = Product::byCode($productCode)->firstOrFail(); 
        }

        $category = $product->category;
        return view('single-product', compact('category', 'product'));
    }

    public function subscribe(SubscriptionRequest $request, Product $product) {

        Subscription::create([
            'email' => $request->email,
            'product_id' => $product->id
        ]);

        return redirect()->back()->with('success', 'Спасибо, мы сообщим когда товар появится в наличии');
    }

    public function changeLocale($locale) {

        $locales = ['en', 'ru'];

        if (in_array($locale, $locales)) {
            session(['locale'=> $locale]);
        }

        return redirect()->back();
    }

    public function changeCurrency($currencyCode) {
        $currency = Currency::byCode($currencyCode)->firstOrFail();
        session(['currency' => $currency->code]);
        return redirect()->back();
    }
}