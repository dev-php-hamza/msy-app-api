<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CouponList extends Model
{
    protected $fillable =[
    	'coupon_id', 'list_id', 'checked'
    ];
}
