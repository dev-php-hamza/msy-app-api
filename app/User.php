<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name', 'email', 'password', 'google_id', 'facebook_id', 'role_id', 'player_id', 'is_verified', 'verification_code', 'verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function role()
    {
      return $this->belongsTo('App\Role');
    }

    public function userInfo()
    {
        return $this->hasOne('App\UserInfo');
    }

    public function lists()
    {
      return $this->hasMany('App\Ulist');
    }

    public function massycard()
    {
      return $this->hasOne('App\Massycard');
    }
    
    public function notifications()
    {
      return $this->belongsToMany(Notification::class, 'notification_users')->withPivot('read');
    }

    public function readNotifications($country_id)
    {
      return $this->notifications()->where('country_id', $country_id)->where('read', 1);
    }

    public function unreadNotifications($country_id)
    {
      return $this->notifications()->where('country_id', $country_id)->where('read', 0);
    }

    public function notificationsCountry($country_id)
    {
      return $this->notifications()->where('country_id', $country_id)->get();
    }

    public function orders()
    {
      return $this->hasMany('App\Order');
    }
    /**
    * Check one role
    * @param string $role
    */
    public function hasRole($role)
    {
      return null !== $this->role()->where('name', $role)->first();
    }

    public function fullName()
    {
        return trim($this->first_name.' '.$this->last_name);
    }
}
