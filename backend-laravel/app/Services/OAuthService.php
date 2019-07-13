<?php

namespace App\Services;

use App\Libraries\PHPMailer\PHPMailer\OAuth;
use App\Exceptions\AuthFailException;
use App\Services\{UserService};
use League\OAuth2\Client\Provider\Google;
use Illuminate\Support\Facades\Config;

class OAuthService extends OAuth
{
    static public $OAuthPool;
    static public $service;
    static public function getService(){
        if(!OAuthService::$service) OAuthService::$service = new OAuthService([]);
        return OAuthService::$service;
    }

    static public function CreateOAuth( $email ){
        $userData = UserService::getService()->getUserData($email);
        $provider = new Google([
            'clientId' => Config::get('google.client_id'),
            'clientSecret' => Config::get('google.client_secret')
        ]);
        $newOAuth = new OAuthService([
            'provider' => $provider,
            'clientId' => Config::get('google.client_id'),
            'clientSecret' => Config::get('google.client_secret'),
            'refreshToken' => $userData->authToken->refresh_token,
            'userName' => $email
        ]);
        return $newOAuth;
    }
    static public function GetOAuth( $email )
    {
        if ( !isset(OAuthService::$OAuthPool[$email])){
            $newOAuth = OAuthService::CreateOAuth($email);
            if ($newOAuth){
                OAuthService::$OAuthPool[$email] = $newOAuth;
            }else{
                throw new AuthFailException();
            }
        }
        return OAuthService::$OAuthPool[$email];
    }
}
