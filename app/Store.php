<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
    	'name', 'storecode', 'address_line_one', 'address_line_two', 'country_id', 'location_id', 'email', 'lat', 'lon', 'image', 'delivery_company_name', 'delivery_company_email', 'delivery', 'curbside'
    ];

    public function storeInfo()
    {
    	return $this->hasOne('App\StoreInfo');
    }

    public function country()
    {
    	return $this->belongsTo('App\Country');
    }

    public function location()
    {
    	return $this->belongsTo('App\Location');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_stores')
        ->withPivot('price', 'quantity');
    }

    public function orders()
    {
      return $this->hasMany('App\Order');
    }

    public function deliveryCompanies()
    {
        return $this->belongsToMany(DeliveryCompany::class, 'deliverycompany_stores');
    }

    public function getImage()
    {
        if(is_null($this->image) || empty($this->image) || $this->image == ''){
          return $this->image;
        }
        
        if (strpos($this->image, 'http') !== false) {
            return $this->image;
        }
        return url('/').'/store/images/'.$this->image;
    }
}
