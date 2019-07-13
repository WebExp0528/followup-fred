<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthToken extends Model
{
	/**
	 * The attributes that aren't mass assignable.
	 */
	protected $guarded = [];
    // Get the user this auth key belongs to
	public function user() {
		return $this->belongTo('App\Models\User', 'email', 'email');
	}
}
