<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerCare extends Model
{
    protected $fillable =[
    	'email', 'customer_feedback_email', 'massy_card_support_email', 'massy_app_tech_support_email', 'phone', 'country_id',
    ];

    public function country()
    {
    	return $this->belongsTo('App\Country');
    }
}
