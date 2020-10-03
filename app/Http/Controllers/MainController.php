<?php

namespace App\Http\Controllers;

use App\Models\Sku;
use App\Models\Product;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\SubscriptionRequest;
use App\Http\Requests\ProductsFilterRequest;

class MainController extends Controller
{
    public function index(ProductsFilterRequest $requset) {
        $skuQuery = Sku::with(['product.category']);

        if ($requset->filled('price_from')) {
            $skuQuery->where('price', '>=', $requset->price_from);
        }

        if ($requset->filled('price_to')) {
            $skuQuery->where('price', '<=', $requset->price_to);
        }

       // $checkboxes = ['hit', 'new', 'recommend'];

        /*if ($requset->hasAny($checkboxes)) {
            $skuQuery->where(function($query ) use ($requset, $checkboxes) {
                foreach($checkboxes as $field) {
                    $requset->has($field) && $query->$field();
                }
            });
        }*/

        foreach(['hit', 'new', 'recommend'] as $field) {
            if ($requset->has($field)) {
                $skuQuery->whereHas('product', function ($query) use ($field) {
                    $query->where($field, '=', '1');
                });
            } 
        }

        $skus = $skuQuery->paginate(6);

        return view('index', compact('skus'));
    }

    public function categories() {
        return view('categories');
    }

    public function singleCategory($categoryCode) {
        $category = Category::where('code', $categoryCode)->firstOrFail();
        return view('single-category', compact('category'));
    }

    public function sku($categoryCode, $productCode, Sku $sku) {

        if ($sku->product->code != $productCode ) {
            abort(404, 'Product not found');
        }

        if ($sku->product->category->code != $categoryCode) {
            abort(404, 'Category not found');
        }

        return view('single-product', compact('sku'));
    }

    public function subscribe(SubscriptionRequest $request, Sku $sku) {

        Subscription::create([
            'email' => $request->email,
            'sku_id' => $sku->id
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