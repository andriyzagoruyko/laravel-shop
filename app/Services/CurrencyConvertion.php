<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\Product;
use Carbon\Carbon;

class CurrencyConvertion
{
    protected static $container;

    public static function loadContainer() {
        if (is_null(self::$container)) {
            $currencies = Currency::get();
            foreach ($currencies as $currency) {
                self::$container[$currency->code] = $currency;
            }
        }
    }

    public static function getCurrencies() {
        return self::$container;
    }

    public static function convert($sum, $originCurrencyCode = 'RUB', $targetCurrencyCode = null) {
        self::loadContainer();

        $originCurrency = self::$container[$originCurrencyCode];

        if ($originCurrency->rate == 0 || $originCurrency->updated_at->startOfDay() != Carbon::now()->startOfDay()) {
            CurrencyRates::getRates();
            self::loadContainer();
            $originCurrency = self::$container[$originCurrencyCode];
        }

        if (is_null($targetCurrencyCode)) {
            $targetCurrencyCode = session('currency', 'RUB');
        }

        $targetCurrency = self::$container[$targetCurrencyCode];

        if ($originCurrency->rate == 0 || $targetCurrency->updated_at->startOfDay() != Carbon::now()->startOfDay()) {
            CurrencyRates::getRates();
            self::loadContainer();
            $targetCurrency = self::$container[$targetCurrencyCode];
        }

        return $sum  / $originCurrency->rate * $targetCurrency->rate;
    }

    public static function getCurrencySymbol($currencyCode = null) {
        self::loadContainer();

        if (is_null($currencyCode)) {
            $currencyCode = session('currency', 'RUB');
        }

        $currency = self::$container[$currencyCode];  

        return $currency->symbol;
    }

    public static function getBaseCurrency() {
        self::loadContainer();

        foreach (self::$container as $currency) {
            if ($currency -> isMain()) {
                return $currency;
            }
        }
    }
}