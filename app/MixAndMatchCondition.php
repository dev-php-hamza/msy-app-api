<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MixAndMatchCondition extends Model
{
    protected $fillable = [
    	'coupon_id',
    	'conditions',
		'selection_quantity'
    ];

    public function coupon()
    {
    	return $this->belongsTo(Coupon::class);
    }
}
