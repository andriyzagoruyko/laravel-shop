<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    public function products() {
        return $this->belongsToMany(Product::class)->withPivot('count')->withTimestamps();
    }

   /* public function user() {
        return $this->belongsTo(User::class);
    }*/

    public function getFullPrice() {
        $sum = 0;

        foreach ($this->products as $product) {
            $sum += $product->getPriceForCount();
        }

        return $sum;
    }

    public function saveOrder($name, $phone) {
        if ($this->status == 0) {
            $this->name = $name;
            $this->phone = $phone;
            $this->status = 1;
            $this->save();
            
            session()->forget('orderId');

            return true;
        }

        return false;
    }

    /*static function getOrCreate() {
        $orderId = session('orderId');
        
        if (is_null($orderId)) {
            $order = Order::create();
            session(['orderId' => $order->id]);
        }
        else {
            $order = Order::find($orderId);
        }

        return $order;
    }*/
}
