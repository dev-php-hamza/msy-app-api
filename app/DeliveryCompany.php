<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryCompany extends Model
{
    protected $fillable = [
    	'name',
    	'email',
    	'icon',
    	'country_id'
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function stores()
    {
    	return $this->belongsToMany(Store::class, 'deliverycompany_stores')->withTimestamps();
    }
}
