<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = [
        'name','country_id','start_date','end_date',
    ];

    public function products()
    {
    	return $this->belongsToMany(Product::class, 'offer_products');
    }

    public function country()
    {
    	return $this->belongsTo('App\Country');
    }
}
