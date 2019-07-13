<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowupLog extends Model
{
	/**
	 * The attributes that aren't mass assignable.
	 */
	protected $guarded = [];
	public function user() {
		return $this->belongsTo('App\Models\User', 'email', 'email');
	}
	public function followup() {
        return $this->belongsTo('App\Models\Followup');
    }

}
