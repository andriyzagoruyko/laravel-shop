<?php

namespace App\Services;

use App\Models\Currency;
use App\Models\Product;

use GuzzleHttp\Client;

class CurrencyRates
{
    public static function getRates() {
        $baseCurrency = CurrencyConvertion::getBaseCurrency();

        $url = config('currency_rates.api_url') . '?base=' . $baseCurrency->code;
        $client = new Client();
        $responce = $client->request('GET', $url);

        if ($responce->getStatusCode() !== 200) {
            throw new Exception('There is a problem with currenct rate service');
        }

        $rates = json_decode($responce->getBody()->getContents(), true)['rates'];

        foreach (CurrencyConvertion::getCurrencies() as $code => $currency) {
            if (!$currency->isMain()) {
                if (!isset($rates[$code])) {
                    throw new Exception('There is a problem with currency: ' . $currency->code);
                } else {
                    $currency->update(['rate' => $rates[$code]]);
                    $currency->touch();
                }
            }
        }
    }
}