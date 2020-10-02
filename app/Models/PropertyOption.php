<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\softDeletes;
use App\Models\Traits\Translatable;

class PropertyOption extends Model
{
    use HasFactory, softDeletes, Translatable;

    protected $fillable = ['property_id', 'name', 'name_en'];

    public function property() {
        return $this->belongsTo(Property::class);
    }

    //TODO: check table name and fiels
    public function skus(){
        return $this->belongsToMany(Sku::class);
    }
}
