<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Exceptions \ {
    NotRegisterException
};
use App\Services\GmailAPIService;
class UserService
{

    static public $service;
    static public function getService()
    {
        if (!UserService::$service) UserService::$service = new UserService();
        return UserService::$service;
    }
    /**
     * @param GoogleAuthCallback $data
     * @return User
     */
    public function createOrUpdateUser($data)
    {
        try {

            // Check if user exists for that email
            $user = User::where('email', $data->email)->first();

            if (!$user) {
                throw new NotRegisterException();
            }

            // Update or create user
            $user->authToken()->updateOrCreate(['email' => $data->email], ['access_token' => $data->token, 'refresh_token' => $data->refreshToken]);

            // Auth user
            Auth::login($user, true);

            return $user;
        } catch (Exception $e) {
            return 'User cannot be created or saved.';
        }
    }

    /**
     * Check if user exists
     */
    public function checkUser($data)
    {
        if (isset($data['timezone'])){
            User::updateOrCreate(['email' => $data["email"]], ['timezone' => $data["timezone"]]);
        }
        if ( GmailAPIService::GetGmailService($data["email"]) ){
            return true;
        }else{
            return false;
        }
    }

    /**
     * Get user data
     */
    public function getUserData($email)
    {
        $user = User::where('email', $email)->first();
        if (!$user) {
            throw new NotRegisterException();
        }
        return $user;
    }
}
