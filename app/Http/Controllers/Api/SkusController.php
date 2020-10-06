<?php

namespace App\Http\Controllers\Api;

use App\Models\Sku;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SkusController extends Controller
{
    public function getSkus() {
        return Sku::with('product')
            ->available()
            ->get()
            ->append('product_name');
    }
}
