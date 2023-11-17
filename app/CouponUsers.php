<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponUsers extends Model
{
    protected $fillable =[
    	'coupon_id', 'user_id', 'active',
    ];
}
