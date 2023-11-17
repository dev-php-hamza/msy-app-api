<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Massycard extends Model
{
    protected $fillable = [
    	'card_holder', 'card_number', 'card_loyalty', 'card_type', 'card_status', 'user_id', 'member_id', 'card_id'
    ];

    public function user()
    {
    	$this->belongsTo('App\User');
    }
}
