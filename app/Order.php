<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTime;
use DateTimeZone;

class Order extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'store_id', 'delivery_company_id', 'order_number', 'user_card_number', 'total_price', 'sent_to_store', 'sent_to_customer'
    ];

    public function user()
    {
    	return $this->belongsTo('App\User');
    }

    public function store()
    {
        return $this->belongsTo('App\Store');
    }

    public function orderProducts()
    {
    	return $this->hasMany('App\OrderProduct');
    }

    public function deliveryDetail()
    {
        return $this->hasOne('App\DeliveryDetail');
    }

    public function deliveryCompany()
    {
        return $this->belongsTo('App\DeliveryCompany')->withDefault();
    }

    public function placeAt()
    {
        $DateTime = new DateTime($this->created_at);
        $DateTime->setTimezone(new DateTimeZone('GMT'));
        $DateTime->modify('-4 hours');
        return $DateTime->format("d M Y h:iA");
    }

    public function products()
    {
        return $this->belongsToMany('App\Product', 'order_products')->withPivot('product_quantity');
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_orders')->withPivot('id');
    }
}
