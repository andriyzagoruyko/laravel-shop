<?php

namespace App\Classes;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;


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

            $this->order = Order::create($data);
            session(['orderId' => $order->id]);
        }
        else {
            $this->order = Order::findOrFail($orderId);
        }
    }

    public function getOrder() {
        return $this->order;
    }

    public function countAvaliable(){

        foreach($this->order->products as $orderProduct)
        {
            if ($orderProduct->count < $this->getPivotRow($orderProduct)->count) {
                return false;
            }
        }

        return true;
    }

    public function saveOrder($name, $phone) {
        if (!$this->countAvaliable()) {
            return false;
        }
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