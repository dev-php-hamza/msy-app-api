<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationUser extends Model
{
    protected $fillable = [
    	'notification_id', 'user_id', 'read',
    ];
}
