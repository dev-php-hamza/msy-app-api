<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AlternateCodes extends Model
{
    protected $fillable = [
    	'master_upc',
		'alternate_code',
		'country_id'
    ];
}
