<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;
use App\Models\Traits\Translatable;

class Property extends Model
{
    use HasFactory, softDeletes, Translatable;
    
    protected $fillable = ['name', 'name_en'];

    public function propertyOptions() {
        return $this->hasMany(PropertyOption::class);
    }

    //TODO: check table name and fiels
    public function products() {
        return $this->belongsToMany(Product::class);
    }
}
 