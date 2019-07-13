<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    // Get followup for this log
    protected $guarded = [];
    public function followup() {
        return $this->belongsTo('App\Models\Followup');
    }
}
