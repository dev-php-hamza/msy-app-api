<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AppIntegration extends Model
{
    protected $fillable = [
    	'app_name',
    	'salt',
    	'base64_salt',
    	'auth_token'
    ];
}
