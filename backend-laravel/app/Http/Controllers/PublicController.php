<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\DB;
use App\Services\GmailAPIService;
use Exception;
use App\Services\EmailService;
use App\Services\SMTPService;

class PublicController extends Controller
{
	//
	public function getTest()
	{
		SMTPService::GetSMTPObject('@gmail.com')->sendEmailWithCustomHeader((object)[
			'from_email' => '@gmail.com',
			'from' => '',
			'to' => 'Gold Time <@gmail.com>, @outlook.com',
			'cc' => '@gmail.com, @gmail.com',
			'reply_id' => '<CALpGRHcD+iZHqFsud4UW8XTqV47YBrMa8GQiL5VM8vfKu4zXGQ@mail.gmail.com>',
			'subject' => 'Test API test',
			'body' => 'testBody'
		], '@gmail.com');
		dd(SMTPService::GetSMTPObject('@gmail.com')->getLastMessageID());
		$message = GmailAPIService::GetGmailService('@gmail.com')->GetMessagesByThreadID('16b2787ab18e1c1d');
		dd($message);
	}
	public function checkdb(Request $request)
	{
		$query = $request->input('query');
		dd(DB::select(DB::raw($query)));
	}
}
