<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OfferProduct extends Model
{
    protected $fillable = [
    	'offer_id', 'product_id',
    ];
}
