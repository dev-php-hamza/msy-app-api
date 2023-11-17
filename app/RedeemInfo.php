<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RedeemInfo extends Model
{
    protected $fillable = [
    	'title', 'image', 'description', 'country_id'
    ];

    public function country()
    {
    	return $this->belongsTo('App\Country');
    }

    public function getImage()
    {
        if (strpos($this->image, 'http') !== false) {
            return $this->image;
        }
        return url('/').'/redeemInfo/images/'.$this->image;
    }
}
