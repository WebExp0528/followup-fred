<?php

namespace App\Services;

use App\Exceptions\AuthFailException;
use App\Libraries\PHPMailer\PHPMailer\PHPMailer;
use App\Services\OAuthService;
use Symfony\Component\VarDumper\VarDumper;

class SMTPService extends PHPMailer
{
    public static $STMP_POOL = [];

    public static $service;
    public $email;
    public static function getService()
    {
        if (!SMTPService::$service) {
            SMTPService::$service = new SMTPService();
        }

        return SMTPService::$service;
    }

    public static function createSMTPObject($email)
    {
        $mail = new SMTPService();
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = 'tls';
        $mail->SMTPAuth = true;
        $mail->AuthType = 'XOAUTH2';
        $mail->setOAuth(OAuthService::GetOAuth($email));
        $mail->email = $email;
        if (!$mail->smtpConnect()) {
            error_log($email . " SMTP error ");
        } else {
            return $mail;
        }
        return false;
    }
    public static function GetSMTPObject($email, $new = false)
    {
        if ($new) {
            return SMTPService::createSMTPObject($email);
        }

        if (isset(SMTPService::$STMP_POOL[$email])) {
            return SMTPService::$STMP_POOL[$email];
        } else {
            $newSmtp = SMTPService::createSMTPObject($email);
            if ($newSmtp) {
                SMTPService::$STMP_POOL[$email] = $newSmtp;
            } else {
                return 'false';
            }
            return SMTPService::$STMP_POOL[$email];
        }
    }


    public function sendEmailWithCustomHeader($data, $toEmail, $customHeader = null)
    {
        $this->clearAllRecipients();
        $this->clearCustomHeaders();
        $this->SetFrom($data->from_email, $data->from);

        $this->AddReplyTo($data->from);

        $this->Subject = $data->subject;

        $this->MsgHTML($data->body);
        $this->addBCC($toEmail);

        if ($customHeader) {
            foreach ($customHeader as $name => $value) {
                $this->addCustomHeader($name, $value);
                error_log('----------- Add header -----------------' . $name . ' ' . $value);
            }
        }
        if (isset($data->to)) {
            $this->addCustomHeader('To', $data->to);
        }
        if (isset($data->cc)) {
            $this->addCustomHeader('Cc', $data->cc);
        }
        if (isset($data->bcc)) {
            $this->addCustomHeader('Bcc', $data->bcc);
        }
        if (isset($data->reply_id)) {
            $this->addCustomHeader('In-Reply-To', $data->reply_id);
            $this->addCustomHeader('References', $data->reply_id);
        }
        if (!$this->send()) {
            error_log(" @@@@@@@@@@@@ Send fail @@@@@@@@@@@ ");
            unset(self::$STMP_POOL[$this->email]);
            return false;
        } else {
            VarDumper::dump("Send success");
        }
        return $this->lastMessageID;
    }
}
