<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Socialite;
use App\Exceptions\AuthFailException;
use App\Http\Requests\CheckAuthRequest;
use App\Constant;
use Illuminate\Http\Request;

class SocialAuthGoogleController extends Controller
{
    /**
     * Redirect function
     */
    public function redirect()
    {
        $scopes = [
            'https://mail.google.com/',
        ];
        return Socialite::driver('google')->scopes($scopes)->with(["access_type" => "offline", "prompt" => "consent select_account"])->redirect();
    }

    /**
     * @param CheckAuthRequest $request ( $email , $timezone )
     *
     */
    public function checkAuth(CheckAuthRequest $request)
    {
        $data = $request->all();
        if (UserService::getService()->checkUser($data)) {
            return response()->json(
                [
                    'error' => "false",
                    "data" => UserService::getService()->getUserData($data['email'])
                ],
                Response::HTTP_OK
            );
        } else {
            throw new AuthFailException();
        }
    }

    public function callback( Request $request )
    {
        try {
            if ($request->input('error')){
                return redirect(Constant::GMAIL_URL);
            }
            $auth_user = Socialite::driver('google')->user();
            $user = UserService::getService()->createOrUpdateUser($auth_user);
            $user = Auth::user()->remember_token;
            return redirect(Constant::GMAIL_URL);
        } catch (Exception $e) {
            return 'error';
        }
    }
}
