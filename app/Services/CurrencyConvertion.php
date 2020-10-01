<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\Product;
use Carbon\Carbon;

class CurrencyConvertion
{
    public const DEFAULT_CURRENCY_CODE = 'RUB';
    protected static $container;

    public static function loadContainer() {
        if (is_null(self::$container)) {
            $currencies = Currency::get();
            foreach ($currencies as $currency) {
                self::$container[$currency->code] = $currency;
            }
        }
    }

    public static function getCurrencyFromSession() {
        return session('currency', self::DEFAULT_CURRENCY_CODE);
    }

    public static function getCurrentCurrencyFromSession() {

        self::loadContainer();

        $currencyCode = self::getCurrencyFromSession();

        foreach (self::$container as $currency) {
            if ($currency->code === $currencyCode) {
                return $currency;
            }
        }
    }

    public static function getCurrencies() {
        return self::$container;
    }

    public static function convert($sum, $originCurrencyCode = self::DEFAULT_CURRENCY_CODE, $targetCurrencyCode = null) {
        self::loadContainer();

        $originCurrency = self::$container[$originCurrencyCode];

        $today = Carbon::now()->startOfDay();

        if ($originCurrency->code != self::DEFAULT_CURRENCY_CODE) {
            $updatedAt =  $originCurrency->updated_at;

            if ($originCurrency->rate == 0 || !$updatedAt->startOfDay()->eq($today)) {
                CurrencyRates::getRates();
                self::loadContainer();
                $originCurrency = self::$container[$originCurrencyCode];
            }
        }

        if (is_null($targetCurrencyCode)) {
            $targetCurrencyCode = self::getCurrencyFromSession();
        }

        $targetCurrency = self::$container[$targetCurrencyCode];

        if ($targetCurrency->code != self::DEFAULT_CURRENCY_CODE) {

            $updatedAt =  $targetCurrency->updated_at;

            if ($targetCurrency->rate == 0 || !$updatedAt->startOfDay()->eq($today)) {
                CurrencyRates::getRates();
                self::loadContainer();
                $targetCurrency = self::$container[$targetCurrencyCode];
            }
        }

        return $sum  / $originCurrency->rate * $targetCurrency->rate;
    }

    public static function getCurrencySymbol($currencyCode = null) {
        self::loadContainer();

        if (is_null($currencyCode)) {
            $currencyCode = self::getCurrencyFromSession();
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