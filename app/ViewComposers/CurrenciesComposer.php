<?php

namespace App\ViewComposers;

use Illuminate\View\View;
use App\Models\Category;

use App\Services\CurrencyConvertion;

class CurrenciesComposer 
{
    public function compose(View $view) {
        $currencies = CurrencyConvertion::getCurrencies();
        $view->with('currencies', $currencies);
    }
}