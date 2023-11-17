<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $fillable = [
      'user_id', 'city', 'country', 'date_of_birth', 'phone_number', 'image',
    ];

    public function getPhoneNumberAttribute($value)
    {
    	return str_replace(array( '(', ')' , ' ', '-'), '', $value);
    }
}
