<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderSettings extends Model
{
    protected $fillable = [
    	'quantity_text', 'pickup_customer_notice_text', 'delivery_customer_notice_text', 'order_services_text', 'welcome_text', 'completion_text', 'customer_email_text','primary_email', 'cc_email_addresses','minimum_order_price', 'country_id', 'massy_card_required'
    ];

    public function country()
    {
    	return $this->belongsTo('App\Country');
    }
}
