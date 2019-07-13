<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
	// Get the followup this email belongs to
	protected $guarded = [];
	public function followup() {
		return $this->hasOne('App\Models\Followup','email_id','id');
	}
}
