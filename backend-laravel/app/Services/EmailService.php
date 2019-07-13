<?php

namespace App\Services;

use App\Models\Email;

class EmailService
{

    /**
     * Create new mail
     *
     * @param array $data
     * from
     * to
     * cc
     * bcc
     * message_id
     * subject
     * body
     * @return Email
     */
    public static $emailService;
    public static function getService()
    {
        if (!EmailService::$emailService) {
            EmailService::$emailService = new EmailService();
        }

        return EmailService::$emailService;
    }
    public function createMail($data)
    {
        return Email::updateOrCreate(["message_id" => $data["message_id"]], $data);
    }
    public function getRealEmail($emails)
    {
        $emails = str_replace(' ', '', $emails);
        $arr = explode(',', $emails);
        $ret = [];
        foreach ($arr as $email) {
            $emailSplit = preg_split("/[<>]/", $email);
            if (sizeof($emailSplit) > 1) {
                $ret[] = $emailSplit[1];
            } else if (strlen($emailSplit[0])) {
                $ret[] = $emailSplit[0];
            }

        }
        return implode(',', $ret);
    }
    public function getMessage($query)
    {
        return Email::where($query)->first();
    }
}
