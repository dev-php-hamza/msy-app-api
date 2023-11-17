<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponOrderProduct extends Model
{
    protected $fillable =[
    	'coupon_order_id', 'product_id', 'quantity', 'type'
    ];
}
