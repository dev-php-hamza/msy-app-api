<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ListProduct extends Model
{
    protected $fillable = [
    	'list_id', 'product_id', 'product_quantity', 'checked',
    ];
}
