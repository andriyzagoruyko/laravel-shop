<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;
use App\Models\Traits\Translatable;
use App\Services\CurrencyConvertion;

class Product extends Model
{
    use HasFactory, softDeletes, Translatable;

    protected $fillable = [
        'code', 'name', 'name_en', 'category_id', 'description', 'description_en', 'image', 'hit', 'new', 'recommend', 'count'
    ];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function skus() {
        return $this->hasMany(Sku::class);
    }
    
    //ToDO: check table name for relation
    public function properties() {
        return $this->belongsToMany(Property::class, 'property_product')->withTimestamps();
    }

    public function getCategory() {
        return Category::find($this->category_id)->name;
    }


    public function scopeByCode($query, $code) {
        return $query->Where('code', $code);
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
