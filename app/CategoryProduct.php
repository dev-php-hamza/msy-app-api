<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    protected $fillable = [
    	'prodcut_id',
    	'category_id'
    ];
}
