<?php

namespace App\Http\Controllers\Person;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Auth::user()->orders()->where('status', 1)->get();
        return view('auth.orders.index', compact('orders'));
    }

    public function show(Order $order) {
        if (!Auth::user()->orders->contains($order)) {
            return redirect()->route('person.orders.index');
        }
        return view('auth.orders.show', compact('order'));
    }
}
