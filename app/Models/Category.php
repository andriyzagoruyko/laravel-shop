<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\Translatable;

class Category extends Model
{
    use HasFactory, Translatable;

    protected $fillable = ['code', 'name', 'name_en', 'description', 'description_en', 'image'];

    public function products() {
        return $this->hasMany(Product::class);
    }
}
