<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Frequency extends Model
{
    // Get all followups for this frequency
    protected $guarded = [];
    protected $table = 'frequencies';

    static public function getSendTimes(){
        return Frequency::where('id', '!=', '-1')->orderBy('hours')->orderBy('minutes')->get();
    }
}
