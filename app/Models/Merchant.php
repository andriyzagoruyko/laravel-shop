<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Merchant extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'token'];

    public function createToken() {
        $token = Str::random(64);
        $this->token = hash('sha256', $token);
        $this->save();

        return $token;
    }
}
