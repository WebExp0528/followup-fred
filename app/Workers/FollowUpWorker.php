<?php

namespace App\Workers;

use App\Jobs\FollowUpJob;
use App\Services \ {
FollowUpService
};
class FollowUpWorker
{
    public function runFollowUp()
    {
        $followUpEmails = FollowUpService::getService()->getFollowUps();
        foreach ($followUpEmails as $key => $followup) {
            # code...
            // error_log(json_encode($followup->email));
            if (!$followup->email) {
                $followup->delete();
            } else {
                $followup->deactiveFollowUp();
                FollowUpJob::dispatch($followup);
            }
        }

        error_log("ok");
    }
}
