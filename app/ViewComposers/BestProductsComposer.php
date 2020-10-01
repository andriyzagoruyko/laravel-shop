<?php

namespace App\ViewComposers;

use Illuminate\View\View;
use App\Models\Product;
use App\Models\Order;


class BestProductsComposer
{
    public function compose(View $view) {
        $bestProducts = Order::get()->map->products->flatten()->map->pivot->mapToGroups(function ($pivot) {
            return [$pivot->product_id => $pivot->count];
        })->map->sum()->sortByDesc(null)->take(3)->keys()->toArray();

        $bestProducts = Product::whereIn('id',$bestProducts)->get();
        $view->with('bestProducts', $bestProducts);
    }
}