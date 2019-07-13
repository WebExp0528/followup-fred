<?php

namespace App\Services;

use Google_Client;
use Google_Service_Gmail;
use App\Services\UserService;
use App\Models\Email;
use App\Services\EmailService;
use App\Exceptions\GeneralException;
use App\Exceptions\AuthFailException;
use Log;
use Symfony\Component\VarDumper\VarDumper;

class GmailAPIService extends Google_Client
{
    static public $GmailAPIPool = [];
    public $email;
    /**
     * @var Google_Service_Gmail $service
     */
    public $service;
    static public function CreateNewClient($email)
    {
        error_log('create new service '.$email);
        $user = UserService::getService()->getUserData($email);
        if (!$user->authToken) throw new AuthFailException();
        $client = new GmailAPIService();
        $client->email = $email;
        $client->setApplicationName(config('google.application_name'));
        $client->setAuthConfig(config('google'));
        $client->setAccessToken([
            'access_token' => $user->authToken->access_token,
            'refresh_token' => $user->authToken->refresh_token,
            'expires_in' => 0
        ]);
        return $client;
    }
    /**
     * @param string $email
     * @return GmailAPIService $service
     */
    static public function GetGmailService($email)
    {
        if (!isset(GmailAPIService::$GmailAPIPool[$email])) {
            error_log('From pool '.$email);
            GmailAPIService::$GmailAPIPool[$email] = GmailAPIService::CreateNewClient($email);
        }else{
            error_log('From pool '.$email);
        }
        GmailAPIService::$GmailAPIPool[$email]->Token_Refresh();
        return GmailAPIService::$GmailAPIPool[$email];
    }
    public function Token_Refresh()
    {
        error_log('refresh '.$this->getRefreshToken().' '.$this->email);
        $this->fetchAccessTokenWithRefreshToken($this->getRefreshToken());
        $this->service = new Google_Service_Gmail($this);
        if (!$this->service) throw new AuthFailException();
    }
    /**
     * @param string $messageID
     * @return Email $message
     */
    public function GetMessageByMessageID( $messageID, $gmail_service_mail = false ){
        $email = EmailService::getService()->getMessage(['message_id'=>$messageID]);
        if ($email) return $email;
        $message = $this->service->users_messages->get($this->email, $messageID, ['format'=>'full']);
        if ( $gmail_service_mail ) return $message;
        return UtilsServices::getService()->ConvertGmailMessageToEmail($message);
    }
    /**
     * @param string $messageID
     * @return Email $message
     */
    public function GetMessagesByThreadID( $threadID, $gmail_service_mail = false  ){
        $thread = $this->service->users_threads->get($this->email, $threadID);
        $messages = $thread->getMessages();

        Log::info($messages);

        if ( $gmail_service_mail ) return $messages;
        $ret = [];
        foreach($messages as $message){
            $ret[] = UtilsServices::getService()->ConvertGmailMessageToEmail($message);
        }
        return $ret;
    }

    /**
     * @param string $messageID
     * @return bool $result
     */
    public function DeleteMessageByMessageID( $messageID ){
        $this->service->users_messages->delete($this->email, $messageID);
    }
}
