<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductStore extends Model
{
    protected $fillable = [
    	'product_id', 'store_id', 'price', 'quantity',
    ];
}
