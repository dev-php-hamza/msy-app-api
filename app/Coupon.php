<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
    	'title', 'short_description', 'description', 'start_date', 'start_time', 'end_date', 'end_time', 'country_id', 'coupon_type', 'image', 'barcode', 'active', 'promotion_id', 'mix_and_match_type'
    ];

    public function products()
    {
    	return $this->belongsToMany(Product::class, 'coupon_products')->withPivot('quantity', 'total_price', 'discount_price', 'discount_percentage', 'type', 'discount_type');
    }

    public function country()
    {
    	return $this->belongsTo(Country::class);
    }

    // public function scopeActive($query)
    // {
    //     return $query->whereDate('start_date','<=', date('Y-m-d'))->whereDate('end_date','>=', date('Y-m-d'))->orderBy('created_at', 'desc');
    // }

    public function scopeActive($query)
    {
        return $query->where('active', 1)->orderBy('created_at', 'desc');
    }

    public function scopeExpire($query)
    {
        return $query->where('active', 0)->orderBy('created_at', 'desc');
    }

    // public function scopeExpire($query)
    // {
    //     return $query->whereDate('end_date','<', date('Y-m-d'))->orderBy('created_at', 'desc');
    // }

    public function getImage()
    {
        // if(is_null($this->image) || empty($this->image) || $this->image == ''){
        //   return $this->image;
        // }
        
        if (strpos($this->image, 'http') !== false) {
            return $this->image;
        }
        return url('/').'/coupon/images/'.$this->image;
    }

    public function bundle()
    {
        return $this->hasOne(Bundle::class);
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class);
    }

    public function mix_and_match_conditions()
    {
        return $this->hasMany(MixAndMatchCondition::class);
    }

    public function isActive()
    {
        if($this->active == 1){
            return true;
        }
        return false;
    }
}
