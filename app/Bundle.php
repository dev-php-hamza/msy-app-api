<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    protected $fillable = [
    	'coupon_id',
    	'name',
		'number',
		'bundle_price'
    ];

    public function coupon()
    {
    	return $this->belongsTo(Coupon::class);
    }
}
