<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponProduct extends Model
{
    protected $fillable =[
    	'coupon_id', 'product_id', 'quantity', 'total_price', 'discount_price', 'discount_percentage', 'type', 'bundle_id', 'discount_type'
    ];
}
