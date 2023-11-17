<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ulist extends Model
{
	/**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lists';

    protected $fillable = [
      'name', 'user_id',
    ];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function products()
    {
        return $this->belongsToMany('App\Product', 'list_products', 'list_id', 'product_id')->withPivot('product_quantity','checked');
    }

    public function coupons()
    {
        return $this->belongsToMany('App\Coupon', 'coupon_lists', 'list_id', 'coupon_id')->withPivot('id');
    }

    public function checkedProducts()
    {
        return $this->products()->where('checked', 1)->get();
    }

    public function uncheckedProducts()
    {
        return $this->products()->where('checked', 0)->get();
    }

    public function checkedBundles()
    {
        return $this->coupons()->where('checked', 1)->get();
    }

    public function uncheckedBundles()
    {
        return $this->coupons()->where('checked', 0)->get();
    }
}
