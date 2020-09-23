<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\Subscription;


class ProductObserver
{
        /**
     * Handle the product "updating" event.
     *
     * @param  \App\Models\Product  $product
     * @return void
     */
    public function updating(Product $product)
    {
        $oldCount = $product->getOriginal('count');

        if ($oldCount == 0 && $product->count > 0) {
            Subscription::sendEmailBySubscription($product);
        }
    }
}
