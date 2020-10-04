<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Services\CurrencyConvertion;


class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer(['layouts.master', 'categories'], 'App\ViewComposers\CategoriesComposer');
        View::composer(['layouts.master'], 'App\ViewComposers\BestProductsComposer');
        View::composer(['layouts.master', 'auth.coupons.form'], 'App\ViewComposers\CurrenciesComposer');

        View::composer('*', function($view) {
            $currencySymbol = CurrencyConvertion::getCurrencySymbol();
            $view->with('currencySymbol', $currencySymbol);
        });
    }
}
