<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreInfo extends Model
{
    protected $fillable = [
    	'store_id', 'phone_number', 'website', 'primary_category', 'sunday_hours_from',
    	'sunday_hours_to', 'monday_hours_from', 'monday_hours_to', 'tuesday_hours_from',
    	'tuesday_hours_to', 'wednesday_hours_from', 'wednesday_hours_to',
    	'thursday_hours_from', 'thursday_hours_to', 'friday_hours_from', 'friday_hours_to', 'saturday_hours_from', 'saturday_hours_to',
    ];
}
