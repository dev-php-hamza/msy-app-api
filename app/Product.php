<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
      'upc', 'desc', 'size', 'item_packing', 'unit_retail', 'regular_retail', 'is_scalable', 'country_id', 'is_showable', 'is_searchable', 'has_images',
    ];

    protected $hidden = ['created_at','updated_at'];

    public function images()
    {
      return $this->hasMany('App\ProductImage')->latest();
    }

    public function promotions()
    {
      return $this->belongsToMany(Promotion::class, 'product_promotions');
    }

    public function stores()
    {
      return $this->belongsToMany(Store::class, 'product_stores')->withPivot('price', 'quantity');
    }

    public function offers()
    {
      return $this->belongsToMany(Offer::class, 'offer_products');
    }

    public function coupons()
    {
      return $this->belongsToMany(Coupon::class, 'coupon_products')->withPivot('quantity', 'total_price', 'discount_price', 'discount_percentage');
    }

    public function lists()
    {
        return $this->belongsToMany('App\Ulist', 'list_products', 'product_id', 'list_id')->withPivot('checked');
    }

    public function orders()
    {
      return $this->belongsToMany('App\Order', 'order_products');
    }

    public function country()
    {
      return $this->belongsTo('App\Country');
    }

    public function categories()
    {
      return $this->belongsToMany('App\Category', 'category_products')->withTimestamps();
    }

    public function categoryNames()
    {
        $catNames = $this->categories()->pluck('name');
        $output = null;
        if (isset($catNames) && !empty($catNames) && $catNames != '' && count($catNames) > 0) {
            foreach ($catNames as $key => $name) {
                $output .= $name.', ';
            }
            $output = rtrim($output, ', ');
        }

        return $output;
    }

    // public function getUnitRetailAttribute($value)
    // {
    //   if ($this->country_id === 4) {
    //     return 0.00;
    //   }
    //   return $value;
    // }

    public function messuringUnit()
    {
        if ($this->is_scalable) {
            $unit = Helper::getMeasuringUnit($this->size);
            switch ($unit) {
                case 'KG':
                case 'OZ':
                case 'G':
                    $unit = 'lb';
                    break;
            }
        }else{
            $unit = $this->size;
        }

        return $unit;
    }

    public function subPrice()
    {
        // $unit = $this->messuringUnit();

        if ($this->is_scalable && (stripos($this->size, 'CT') === false)) {
            $subPrice = Helper::getSubPrice($this->size, $this->unit_retail);
        }else{
            $subPrice = $this->unit_retail;
        }

        return $subPrice;
    }

    public function bundle()
    {
        return $this->belongsTo('App\Bundle');
    }
}
