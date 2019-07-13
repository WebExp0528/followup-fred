<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Frequency;
use App\Constant;
use Carbon\Carbon;
use Symfony\Component\VarDumper\VarDumper;

class Followup extends Model
{
    // Dates
    protected $dates = [
        'created_at',
        'updated_at',
        'completed_at',
    ];

    // Get the user this followup belongs to
    protected $guarded = [];
    public function user()
    {
        return $this->belongsTo('App\Models\User','sender_email','email');
    }

    // Get email for this followup
    public function email()
    {
        return $this->belongsTo('App\Models\Email', 'email_id', 'id');
    }

    // Get logs for this followup
    public function logs()
    {
        return $this->hasMany('App\Models\FollowupLog');
    }

    //Functions for FollowUp table actions


    public function nextFollowUp()
    {
        $send_time = Frequency::getSendTimes();
        $carbonObj = Carbon::createFromTimestamp($this->send_timestamp, $this->user->timezone);
        $this->sent_count = strval(1 + intval($this->sent_count));
        if ($this->followup_type) {
            $carbonObj = $carbonObj->addDay($this->followup_type);
        } else {
            $sendTimeNumber = $this->sent_count % sizeof($send_time);
            if ( $sendTimeNumber == 0) $carbonObj = $carbonObj->addDay(1);
            $carbonObj = $carbonObj->startOfDay()->setHour($send_time[$sendTimeNumber]->hours)->setMinute($send_time[$sendTimeNumber]->minutes);
        }
        if (intval($this->sent_count) >= Constant::LIMIT_FOLLOWUP) {
            $this->completeFollowUp();
        }
        $this->send_timestamp = $carbonObj->timestamp;
        $this->send_dateTime = $carbonObj->toString();
        $this->save();
    }
    public function initFollowUp()
    {
        $carbonObj = Carbon::now()->setTimezone($this->user->timezone);
        if ($this->followup_type) {
            $carbonObj = $carbonObj->addDay(1);
        } else {
            $send_time = Frequency::getSendTimes();
            $carbonObj = $carbonObj->addDay(1)->startOfDay()->setHour($send_time[0]->hours)->setMinute($send_time[0]->minutes);
        }
        $this->send_timestamp = $carbonObj->timestamp;
        $this->send_dateTime = $carbonObj->toString();
        $this->save();
    }
    public function completeFollowUp( $responded = false ){
        $this->completed_at = date('Y-m-d H:i:s', time());
        $this->status = 2;
        if ($responded) {
            $this->responded = 1;
            VarDumper::dump('Completed By Response');
        } else{
            VarDumper::dump('Completed By Limit');
        }
        $this->save();
    }
    public function saveToEmails( $emails ){
        $this->to_emails = implode(',', $emails);
        if (!sizeof($emails)){
            $this->completeFollowUp(true);
        }
        $this->save();
    }
    public function deactiveFollowUp(){
        $this->status = 1;
        $this->save();
    }
    public function activeFollowUp(){
        $this->status = 0;
        $this->save();
    }
}
