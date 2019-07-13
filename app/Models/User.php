<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $guarded = [];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',"id","created_at","updated_at"
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // Get all followups for this user
    public function followups() {
        return $this->hasMany('App\Models\Followup','sender_email','email');
    }

    // Get all followup logs for this user
    public function followupLogs() {
        return $this->hasMany('App\Models\Followup','email','email');
    }

    // Get all auth tokens for this user
    public function authToken() {
        return $this->hasOne('App\Models\AuthToken','email','email');
    }
}
