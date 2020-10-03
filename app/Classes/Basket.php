<?php

namespace App\Classes;

use App\Models\Sku;
use App\Models\Order;
use App\Models\Product;
use App\Mail\OrderCreated;
use App\Services\CurrencyConvertion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;




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

        $skus = collect([]);

        foreach($this->order->skus as $orderSku)
        { 
            $sku = Sku::find($orderSku->id);
            if ($orderSku->countInOrder > $sku->count) {
                return false;
            }

            if ($updateCount) {
                $sku->count -= $orderSku->countInOrder;
                $skus->push($sku);
            }
        }

        if ($updateCount) {
            $skus->map->save();
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

    public function addSku(Sku $sku) {

        if ($this->order->skus->contains($sku)) {
            $pivotRow = $this->order->skus->where('id', $sku->id)->first();

            if ( $pivotRow->countInOrder >= $sku->count) {
                return false;
            }

            $pivotRow->countInOrder++;

        }else{
            if ($sku->count <= 0) {
                return false;
            } 
            $sku->countInOrder = 1;
            $this->order->skus->push($sku);
        }

        return true;
    }

    public function removeSku(Sku $sku) {

        if ($this->order->skus->contains($sku)) {

            $pivotRow = $this->order->skus->where('id', $sku->id)->first();

            if ($pivotRow->countInOrder <= 1) {
                $this->order->skus->pop($pivotRow);
            }else {
                $pivotRow->countInOrder--;
            }
        }
    }
}