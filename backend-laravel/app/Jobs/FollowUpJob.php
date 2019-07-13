<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\{Followup, FollowupLog};
use App\Services \ {
    SMTPService,
    FollowupContentService,
    EmailService,
    GmailAPIService,
    UtilsServices
};
use function GuzzleHttp\json_encode;
use Symfony\Component\VarDumper\VarDumper;
use Carbon\Carbon;
use Log;

class FollowUpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $followup;
    public $tries = 3;
    public function __construct($followup)
    {
        //
        $this->followup = $followup; // this is same email
        error_log(json_encode($this->followup));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(Followup $followup)
    {
        //
        $toEmailArr = explode(',', $this->followup->to_emails);
        if (!sizeof($toEmailArr)) return;
        //Get all Mails by threadID
        $emailsWithThreadId = GmailAPIService::GetGmailService($this->followup->sender_email)->GetMessagesByThreadID($this->followup->thread_id);
        if ($emailsWithThreadId) {
            foreach ($emailsWithThreadId as $email) {
                if (in_array($email->from_email, $toEmailArr) &&
                    (new Carbon($this->followup->email->date))->timestamp <
                    (new Carbon($email->date))->timestamp) {
                    VarDumper::dump('This user replied so removed from to array ' . $email->from_email);
                    unset($toEmailArr[array_search($email->from_email, $toEmailArr)]);
                }
            }
        }
        $followupContent = FollowupContentService::getService()->GetFollowupContent(intval($this->followup->sent_count));

        // Add logs
        $log = FollowupLog::create([
            'email' => $this->followup->sender_email,
            'subject' => $this->followup->email->subject,
            'to_emails' => implode(', ', $toEmailArr),
            'followup_id' => $this->followup->id,
            'sent_dateTime' => $this->followup->send_dateTime]);
        $sentEmailMessage_IDs = [];
        foreach ($toEmailArr as $key => $toEmail) {
            if ($toEmail) {
                VarDumper::dump('Send email to ' . $toEmail);
                $temp = $this->followup->email->body;
                $this->followup->email->body = $this->followup->send_dateTime.'<br>'.$followupContent . '<br><br><br>' . $temp;
                SMTPService::GetSMTPObject($this->followup->sender_email)->sendEmailWithCustomHeader($this->followup->email, $toEmail);
                $sentEmailMessage_IDs[] = SMTPService::GetSMTPObject($this->followup->sender_email)->getLastMessageID();
                $this->followup->email->body = $temp;
            } else {
                unset($toEmailArr[$key]);
            }
        }

        // Remove sent emails else one email
        unset($sentEmailMessage_IDs[0]);
        // Get all message with ThreadID
        $emailsWithThreadId = GmailAPIService::GetGmailService($this->followup->sender_email)->GetMessagesByThreadID($this->followup->thread_id);
        error_log(' message ids needed to delete');
        VarDumper::dump($sentEmailMessage_IDs);
        foreach($sentEmailMessage_IDs as $sentMessage_id){
            GmailAPIService::GetGmailService($this->followup->sender_email)->DeleteMessageByMessageID(UtilsServices::getService()->GetMessage_idFromGmailEmails($sentMessage_id, $emailsWithThreadId));
        }

        $this->followup->saveToEmails($toEmailArr);
        $this->followup->activeFollowUp();
        $this->followup->nextFollowUp();
        VarDumper::dump('----------- End one followup record -------------');
        return;
    }
}
