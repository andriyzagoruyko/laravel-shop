<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;

class BasketController extends Controller
{
    public function basket() {
        $orderId = session('orderId');

        if (!is_null($orderId)) {
            $order = Order::findOrFail($orderId);
        }

        return view('basket', compact('order'));
    }

    public function basketPlace() {
        $orderId = session('orderId');
        
        if (is_null($orderId)) {
            return redirect()->route('home');
        }

        $order = Order::find($orderId);

        return view('basket-place', compact('order'));
    }

    public function basketConfirm(Request $request) {
        $orderId = session('orderId');
        
        if (is_null($orderId)) {
            return redirect()->route('index');
        }

        $order = Order::find($orderId);
        $success = $order->saveOrder($request->input('name'), $request->input('phone'));

        if ($success) {
            Order::eraseOrderSum(0);
            session()->flash('success', 'Ваш заказ принят в обработку');
        } else{
            session()->flash('warning', 'Случилась ошибка');
        }

        return redirect()->route('index');
    }

    public function basketAdd($productId) {
        $orderId = session('orderId');
        
        if (is_null($orderId)) {
            $order = Order::create();
            session(['orderId' => $order->id]);
        }
        else {
            $order = Order::find($orderId);
        }

        if ($order->products->contains($productId)) {
            $pivotRow = $order->products()->where('product_id', $productId)->first()->pivot;
            $pivotRow->count++;
            $pivotRow->update();
        }else{
            $order->products()->attach($productId);
        }

        if (Auth::check()) {
            $order->user_id = Auth::id();
            $order->save();
        }

        $product = Product::find($productId);

        Order::changeFullSum($product->price);

        session()->flash('success', 'Добавлено в корзину: ' .  $product->name);

        return redirect()->route('basket');
    }

    public function basketRemove($productId) {
        $orderId = session('orderId');
        
        if (is_null($orderId)) {
            return redirect()->route('basket');
        }

        $order = Order::find($orderId);

        if ($order->products->contains($productId) ) {
            $pivotRow = $order->products()->where('product_id', $productId)->first()->pivot;

            if ($pivotRow->count <= 1) {
                $order->products()->detach($productId);
            }else {
                $pivotRow->count--;
                $pivotRow->update();
            }

            $product = Product::find($productId);

            Order::changeFullSum(-$product->price);
            session()->flash('warning', 'Удалено с корзины: ' .  $product->name);
        }

        return redirect()->route('basket');
    }
}
