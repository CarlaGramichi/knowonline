<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function items(){
        return $this->hasMany(OrderItem::class);
    }

    public function payments(){
        return $this->hasMany(OrderPayment::class);
    }
}
