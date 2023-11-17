<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
    	'title','country_id','type','start_date','end_date', 'image', 'description', 
    ];

    public function products()
    {
    	return $this->belongsToMany(Product::class, 'product_promotions')
        ->withPivot('sale_price');
    }

    public function country()
    {
    	return $this->belongsTo('App\Country');
    }

    public function scopeActive($query)
    {
        return $query->where('start_date','<=', date('Y-m-d H:i:s'))->where('end_date','>=', date('Y-m-d H:i:s'))->orderBy('created_at', 'desc');
    }

    public function scopeExpire($query)
    {
        return $query->where('end_date','<', date('Y-m-d H:i:s'))->orderBy('created_at', 'desc');
    }

    public function getImage()
    {
        if (strpos($this->image, 'http') !== false) {
            return $this->image;
        }
        return url('/').'/promotion/images/'.$this->image;
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }
}
