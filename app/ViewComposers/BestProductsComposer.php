<?php

namespace App\ViewComposers;

use App\Models\Sku;
use App\Models\Order;
use Illuminate\View\View;


class BestProductsComposer
{
    public function compose(View $view) {
        $bestSkus = Order::get()->map->skus->flatten()->map->pivot->mapToGroups(function ($pivot) {
            return [$pivot->sku_id => $pivot->count];
        })->map->sum()->sortByDesc(null)->take(3)->keys()->toArray();

        $bestSkus = Sku::whereIn('id',$bestSkus)->get();
        $view->with('bestSkus', $bestSkus);
    }
}