<?php

namespace App\Http\Controllers;

use App\Models\Sku;
use App\Models\Order;
use App\Models\Coupon;
use App\Classes\Basket;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddCouponRequest;

class BasketController extends Controller
{
    public function basket() {
        $order = (new Basket())->getOrder();
        return view('basket', compact('order'));
    }

    public function basketPlace() {
        $basket = new Basket();
        $order = $basket->getOrder();
         //dd( $order);   
        if (!$basket->countAvaliable()) {
            session()->flash('warning', 'Товар не доступен для заказа в полном объеме');
            return redirect()->route('basket');
        }

        return view('basket-place', compact('order'));
    }

    public function basketConfirm(Request $request) {

        $basket = new Basket();
        $order = $basket->getOrder();

        if ($order->hasCoupon() && !$order->coupon->AvailableForUse()) {
            $basket->clearCoupon();
            session()->flash('warning', 'Купон не доступен');
            return redirect()->route('basket');
        }

        $email = Auth::check() ? Auth::user()->email :  $request->email;

        if ($basket->saveOrder($request->name, $request->phone, $email)) {
            session()->flash('success', 'Ваш заказ принят в обработку');
        } else{
            session()->flash('warning', 'Товар не доступен для заказа в полном объеме');
        }

        return redirect()->route('index');
    }

    public function basketAdd(Sku $sku) {
        $result= (new Basket(true))->addSku($sku);

        if ($result) {
            session()->flash('success', 'Добавлено в корзину: ' .  $sku->product->name);
        } else {
            session()->flash('warning', 'Товар ' .  $sku->product->name . 'в больше кол-ве не доступен для заказа');
        }

        return redirect()->route('basket');
    }

    public function basketRemove(Sku $sku) {
        (new Basket())->removeSku($sku);

        session()->flash('warning', 'Удалено с корзины: ' .  $sku->product->name);

        return redirect()->route('basket');
    }

    public function setCoupon(AddCouponRequest $request) {
        $coupon = Coupon::where('code', $request->coupon)->first();

        if ($coupon->availableForUse()) {
            (new Basket())->setCoupon($coupon);
            session()->flash('success', 'Купон добавлен к заказу');
        } else {
            session()->flash('warning', 'Купон не может быть использован');
        }

        return redirect()->route('basket');
    }

    public function cleanCoupon() {
        $this->order->coupon()->dissaociate();
    }
}
