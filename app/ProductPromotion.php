<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductPromotion extends Model
{
    protected $fillable = [
    	'promotion_id', 'product_id', 'sale_price',
    ];
}
