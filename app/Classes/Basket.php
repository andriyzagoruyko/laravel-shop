<?php

namespace App\Classes;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Order;
use App\Models\Product;
use App\Mail\OrderCreated;
use App\Services\CurrencyConvertion;




class Basket
{
    protected $order;

    function __construct($createOrder = false) {

        $order = session('order');
        
        if (is_null($order) && $createOrder) {

            $data = [];

            if (Auth::check()) {
                $data['user_id'] = Auth::id();
            }

            $data['currency_id'] = CurrencyConvertion::getCurrentCurrencyFromSession()->id;


            $order = $this->order = new Order($data);
            session(['order' => $order]);
        } else {
            $this->order = $order;
        }
    }

    public function getOrder() {
        return $this->order;
    }

    public function countAvaliable($updateCount = false){

        $products = collect([]);

        foreach($this->order->products as $orderProduct)
        { 
            $product = Product::find($orderProduct->id);
            if ($orderProduct->countInOrder > $product->count) {
                return false;
            }

            if ($updateCount) {
                $product->count -= $orderProduct->countInOrder;
                $products->push($product);
            }
        }

        if ($updateCount) {
            $products->map->save();
        }

        return true;
    }

    public function saveOrder($name, $phone, $email) {
        if (!$this->countAvaliable(true)) {
            return false;
        }

        $result = $this->order->saveOrder($name, $phone);

        if ($result) {
            Mail::to($email)->send(new OrderCreated($name, $this->getOrder()));
        }

        return $result;
    }

    public function addProduct(Product $product) {

        if ($this->order->products->contains($product)) {
            $pivotRow = $this->order->products->where('id', $product->id)->first();

            if ( $pivotRow->countInOrder >= $product->count) {
                return false;
            }

            $pivotRow->countInOrder++;

        }else{
            if ($product->count <= 0) {
                return false;
            } 
            $product->countInOrder = 1;
            $this->order->products->push($product);
        }

        return true;
    }

    public function removeProduct(Product $product) {

        if ($this->order->products->contains($product)) {

            $pivotRow = $this->order->products->where('id', $product->id)->first();

            if ($pivotRow->countInOrder <= 1) {
                $this->order->products->pop($pivotRow);
            }else {
                $pivotRow->countInOrder--;
            }
        }
    }
}