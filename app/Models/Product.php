<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;


class Product extends Model
{
    use HasFactory;
    use softDeletes;

    protected $fillable = [
        'code', 'name', 'category_id', 'description', 'image', 'hit', 'new', 'recommend', 'count'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function getCategory() {
        return Category::find($this->category_id)->name;
    }

    public function getPriceForCount() {
        if (!is_null($this->pivot)) {
            return $this->price * $this->pivot->count;
        }

        return $this->price;
    }

    public function scopeHit($query) {
        return $query->orWhere('hit', 1);
    }

    public function scopeNew($query) {
        return $query->orWhere('new', 1);
    }

    public function scopeRecommend($query) {
        return $query->orWhere('recommend', 1);
    }

    public function scopeByCode($query, $code) {
        return $query->Where('code', $code);
    }

    public function isAvailable() {
        return !$this->trashed() && $this->count > 0;
    }

    public function isHit() {
        return $this->hit === 1;
    }

    public function isNew() {
        return $this->new === 1;
    }

    public function isRecommend() {
        return $this->recommend === 1;
    }
}
