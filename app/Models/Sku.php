<?php

namespace App\Models;

use App\Services\CurrencyConvertion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Sku extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['product_id', 'count', 'price'];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function propertyOptions(){
        return $this->belongsToMany(PropertyOption::class, 'sku_property_option')->withTimestamps();
    }

    public function isAvailable() {
        return !$this->product->trashed() && $this->count > 0;
    }

    public function getPriceForCount() {
        if (!is_null($this->pivot)) {
            return $this->price * $this->pivot->count;
        }

        return $this->price;
    }
    
    public function getPriceAttribute($value) {
        return round(CurrencyConvertion::convert($value), 2);
    }
}
