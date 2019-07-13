<?php

namespace App\Services;

use Google_Service_Gmail_Message;
use App\Models\Email;

class UtilsServices
{
    static public $service;
    static public function getService()
    {
        if (!UtilsServices::$service) UtilsServices::$service = new UtilsServices();
        return UtilsServices::$service;
    }
    public function bigHexToDec($hex)
    {
        $dec = 0;
        $len = strlen($hex);
        for ($i = 1; $i <= $len; $i++) {
            $dec = bcadd($dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
        }
        return $dec;
    }
    /**
     * @param Google_Service_Gmail_Message $serviceMessage
     * @return Email $emails
     */
    public function ConvertGmailMessageToEmail($serviceMessage)
    {
        $email = new Email();
        $email->message_id = $serviceMessage->getId();
        $email->thread_id = $serviceMessage->getThreadId();
        $map = ['Message-ID' => 'reply_id', 'Subject' => 'subject', 'From' => 'from', 'To' => 'to', 'Bcc' => 'bcc', 'Cc' => 'cc', 'Date' => 'date'];
        foreach ($serviceMessage->getPayload()->getHeaders() as  $header) {
            if (isset($map[$header->name])) {
                $email[$map[$header->name]] = $header->value;
            }
        }
        $fromEmail = preg_split("/[<>]/", $email->from);
        if (sizeof($fromEmail) == 1) {
            $email->from_email = $fromEmail[0];
            $email->from = '';
        } else {
            $email->from_email = $fromEmail[1];
            $email->from = $fromEmail[0];
        }
        $body = $serviceMessage->getPayload()->parts[1]->body->data;
		$switched = str_replace(['-', '_'], ['+', '/'], $body);

        $email->body = base64_decode( $switched );
        return $email;
    }
    /**
     * @param Email $emails
     * @param Google_Service_Gmail_Message $gmail_emails
     * @return bool $result
     */
    public function GetMessage_idFromGmailEmails($message_id, $gmail_emails)
    {
        foreach ($gmail_emails as $email) {
            if ($email['reply_id'] == $message_id) {
                return $email['message_id'];
            }
        }
        return false;
    }
}
