<?php

namespace App\Services;

use App\Models\Followup;
use Illuminate\Database\Eloquent\Model;
use App\Constant;
Class FollowUpService
{
    /**
     * Create new follow up mail
     * @param followupData $data
     * @return Model $followup
     */
    static public $followUpService;
    static public function getService(){
        if(!FollowUpService::$followUpService) FollowUpService::$followUpService = new FollowUpService();
        return FollowUpService::$followUpService;
    }

    public function createFollowUp( $data )
    {
        return Followup::create($data);
    }

    /**
     * Get followUps needed to send now
     * @return array FollowUps
     */
    public function getFollowUps(){
        return Followup::where('send_timestamp','<',time() + Constant::OFFSET_FOLLOWUP)->where('status',"0")->where('completed_at',NULL)->get();
    }
}
