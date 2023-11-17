<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
    	'name', 'country_id',
    ];

    public function country()
    {
    	return $this->belongsTo(Country::class);
    }

    public function products()
    {
    	return $this->belongsToMany(Product::class, 'location_products');
    }
}
