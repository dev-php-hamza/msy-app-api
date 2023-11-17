<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = [
    	'object', 'object_id', 'text', 'title', 'country_id', 'total_notification_users', 'total_push_notification_recipients'
    ];

    public function users()
    {
    	return $this->belongsToMany(User::class, 'notification_users')
        ->withPivot('read');
    }

    public function objectTitle()
    {
    	$title = $this->title;
    	if ($this->object == 'promotion') {
    		$object = Promotion::find($this->object_id);
    		$title = $object->title;
    	}
    	if ($this->object == 'coupon') {
    		$object = Coupon::find($this->object_id);
    		$title = $object->title;
    	}
    	return $title;
    }

    public function objectCountry()
    {
        $countryName = '';
        if ($this->object == 'promotion') {
            $object  = Promotion::find($this->object_id);
            $countryName = $object->country->name;
        }
        if ($this->object == 'coupon') {
            $object  = Coupon::find($this->object_id);
            $countryName = $object->country->name;
        }

        if ($this->object == 'text' && $this->country_id != 0) {
            $country = Country::find($this->country_id);
            $countryName = $country->name;
        }
        return $countryName;
    }

    public function country()
    {
        $country = '';
        if ($this->object == 'promotion') {
            $object  = Promotion::find($this->object_id);
            $country = $object->country;
        }
        if ($this->object == 'coupon') {
            $object  = Coupon::find($this->object_id);
            $country = $object->country;
        }

        if ($this->object == 'text' && $this->country_id != 0) {
            $country = Country::find($this->country_id);
        }
        return $country;
    }
}
