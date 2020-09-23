<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\Product;
use App\Mail\OrderCreated;



class Basket
{
    protected $order;

    function __construct($createOrder = false) {

        $orderId =  session('orderId');
        
        if (is_null($orderId) && $createOrder) {

            $data = [];

            if (Auth::check()) {
                $data['user_id'] = Auth::id();
            }

            $order = $this->order = Order::create($data);
            session(['orderId' => $order->id]);
        }
        else {
            $this->order = Order::findOrFail($orderId);
        }
    }

    public function getOrder() {
        return $this->order;
    }

    public function countAvaliable($updateCount = false){
        $orderList = $this->order->products;

        foreach($orderList as $orderProduct)
        {
            $pivotCount = $this->getPivotRow($orderProduct)->count;

            if ($orderProduct->count < $pivotCount) {
                return false;
            }

            if ($updateCount) {
                $orderProduct->count -= $pivotCount;
            }
        }

        if ($updateCount) {
            $orderList->map->save();
        }

        return true;
    }

    public function saveOrder($name, $phone, $email) {
        if (!$this->countAvaliable(true)) {
            return false;
        }

        Mail::to($email)->send(new OrderCreated($name, $this->getOrder()));

        return $this->order->saveOrder($name, $phone);
    }

    protected function getPivotRow($product){
        return $this->order->products()->where('product_id', $product->id)->first()->pivot;
    }

    public function addProduct(Product $product) {

        if ($this->order->products->contains($product->id)) {
            $pivotRow = $this->getPivotRow($product);
            $pivotRow->count++;

            if ($pivotRow->count > $product->count) {
                return false;
            }
            $pivotRow->update();
        }else{
            if ($product->count <= 0) {
                return false;
            } 

            $this->order->products()->attach($product->id);
        }

        Order::changeFullSum($product->price);
        return true;
    }

    public function removeProduct(Product $product) {

        if ($this->order->products->contains($product->id)) {
            $pivotRow = $this->getPivotRow($product);

            if ($pivotRow->count <= 1) {
                $this->order->products()->detach($product->id);
            }else {
                $pivotRow->count--;
                $pivotRow->update();
            }

            Order::changeFullSum(-$product->price);
        }
    }
}