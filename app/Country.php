<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
      'name', 'country_code',
    ];

    public function promotions()
    {
    	return $this->hasMany('App\Promotion');
    }

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function offer()
    {
        return $this->hasOne('App\Offer');
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function customerCare()
    {
        return $this->hasOne('App\CustomerCare');
    }

    public function stores()
    {
        return $this->hasMany('App\Store');
    }

    public function redeemInfo()
    {
        return $this->hasOne('App\RedeemInfo');
    }

    public function mlsSettings()
    {
        return $this->hasMany('App\MlsSettings');
    }

    public function orderSettings()
    {
        return $this->hasOne('App\OrderSettings');
    }

    public function departments()
    {
        return $this->hasMany('App\Department');
    }

    public function categories()
    {
        return $this->hasMany('App\Category');
    }
}
