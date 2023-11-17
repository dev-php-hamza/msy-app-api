<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponOrder extends Model
{
    protected $fillable =[
    	'coupon_id', 'order_id'
    ];
}
