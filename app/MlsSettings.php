<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MlsSettings extends Model
{
    protected $fillable = [
    	'base_url', 'mlid', 'secret_key', 'type', 'switch', 'country_id'
    ];

    public function country()
    {
    	return $this->belongsTo('App\Country');
    }

}
