<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeliveryDetail extends Model
{
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    	'order_id', 'first_name', 'last_name', 'email', 'phone', 'address_line_1', 'address_line_2', 'area','delivery_method', 'license_plate', 
    ];

    public $timestamps = false;
    
    public function order()
    {
    	return $this->belongsTo('App\Order');
    }

    public function fullName()
    {
        return $this->first_name.' '.$this->last_name;
    }
}
