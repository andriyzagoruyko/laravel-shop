<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Product extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'category_id', 'description', 'image', 'hit', 'new', 'recommend'];

    public function category() {
        return $this->belongsTo(Category::class);
    }

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

    /*public function setNewAttribute($value) {
        $this->attributes['new'] = $value === 'on' ? 1 : 0;
    }

    public function setHitAttribute($value) {
        $this->attributes['hit'] = $value === 'on' ? 1 : 0;
    }

    public function setRecommendAttribute($value) {
        $this->attributes['recommend'] = $value === 'on' ? 1 : 0;
    }
*/

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
