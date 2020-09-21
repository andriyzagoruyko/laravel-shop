<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Product extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'category_id', 'description', 'image'];

    public function getCategory() {
        return Category::find($this->category_id)->name;
    }

    public function getUrl() {
        return route('product',[ $this->category->code, $this->code]);
    }

    public function getPriceForCount() {
        if (!is_null($this->pivot)) {
            return $this->price * $this->pivot->count;
        }

        return $this->price;
    }

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
