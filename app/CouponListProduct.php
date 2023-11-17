<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponListProduct extends Model
{
    protected $fillable =[
    	'coupon_list_id', 'product_id', 'quantity', 'type'
    ];
}
